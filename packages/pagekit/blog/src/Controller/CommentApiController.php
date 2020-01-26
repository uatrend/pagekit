<?php

namespace Pagekit\Blog\Controller;

use Pagekit\Application as App;
use Pagekit\Blog\Model\Comment;
use Pagekit\Blog\Model\Post;

/**
 * @Route("comment", name="comment")
 */
class CommentApiController
{
    protected $blog;
    protected $user;

    public function __construct()
    {
        $this->blog = App::module('blog');
        $this->user = App::user();
    }

    /**
     * @Route("/", methods="GET")
     * @Request({"filter": "array", "post":"int", "page":"int", "limit":"int"})
     */
    public function indexAction($filter = [], $post = 0, $page = 0, $limit = 0)
    {
        $query = Comment::query();
        $filter = array_merge(array_fill_keys(['status', 'search', 'order'], ''), $filter);

        extract($filter, EXTR_SKIP);

        if ($post) {
            $query->where(['post_id = ?'], [$post]);
        } elseif (!$this->user->hasAccess('blog: manage comments')) {
            App::abort(403, __('Insufficient user rights.'));
        }

        if (!$this->user->hasAccess('blog: manage comments')) {

            $query->where(['status = ?'], [Comment::STATUS_APPROVED]);

            if ($this->user->isAuthenticated()) {
                $query->orWhere(function ($query) {
                    $query->where(['status = ?', 'user_id = ?'], [Comment::STATUS_PENDING, App::user()->id]);
                });
            }

        } elseif (is_numeric($status)) {
            $query->where(['status = ?'], [(int) $status]);
        } else {
            $query->where(function ($query) {
                $query->orWhere(['status = ?', 'status = ?'], [Comment::STATUS_APPROVED, Comment::STATUS_PENDING]);
            });
        }

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere(['author LIKE ?', 'email LIKE ?', 'url LIKE ?', 'ip LIKE ?', 'content LIKE ?'], array_fill(0, 5, "%{$search}%"));
            });
        }

        $count = $query->count();
        $pages = ceil($count / ($limit ?: PHP_INT_MAX));
        $page = max(0, min($pages - 1, $page));

        if ($limit) {
            $query->offset($page * $limit)->limit($limit);
        }

        if (preg_match('/^(created)\s(asc|desc)$/i', $order, $match)) {
            $order = $match;
        } else {
            $order = [1 => 'created', 2 => App::module('blog')->config('comments.order')];
        }

        $comments = $query->related(['post' => function ($query) {
            return $query->related('comments');
        }])->related('user')->orderBy($order[1], $order[2])->get();

        $posts = [];

        foreach ($comments as $i => $comment) {

            $p = $comment->post;

            if ($post && (!$p || !$p->hasAccess($this->user) || !($p->isPublished() || $this->user->hasAccess('blog: manage comments')))) {
                App::abort(403, __('Post not found.'));
            }

            $comment->content = App::content()->applyPlugins($comment->content, ['comment' => true]);

            $comment->special = count(array_diff($comment->user ? $comment->user->roles : [], [0, 1, 2]));
            $comment->post = null;
            $comment->user = null;

            if ($this->user->hasAccess('blog: manage comments')) {
                $posts[$p->id] = $p;
            } else {
                // unset($comment->ip, $comment->email, $comment->user_id);
                unset($comment->ip, $comment->user_id);
                $comment->email = md5(strtolower($comment->email));
            }
        }

        $comments = array_values($comments);
        $posts = array_values($posts);

        return compact('comments', 'posts', 'pages', 'count');
    }

    /**
     * @Route("/", methods="POST")
     * @Route("/{id}", methods="POST", requirements={"id"="\d+"})
     * @Request({"comment": "array", "id": "int"}, csrf=true)
     * @Captcha(verify="true")
     */
    public function saveAction($data, $id = 0)
    {
        if (!$id) {

            if (!$this->user->hasAccess('blog: post comments')) {
                App::abort(403, __('Insufficient User Rights.'));
            }

            $comment = Comment::create();

            if ($this->user->isAuthenticated()) {
                $data['author'] = $this->user->name;
                $data['email'] = $this->user->email;
                $data['url'] = $this->user->url;
            } elseif ($this->blog->config('comments.require_email') && (!@$data['author'] || !@$data['email'])) {
                App::abort(400, __('Please provide valid name and email.'));
            }

            $comment->user_id = $this->user->isAuthenticated() ? (int) $this->user->id : 0;
            $comment->ip = App::request()->getClientIp();
            $comment->created = new \DateTime;

        } else {

            if (!$this->user->hasAccess('blog: manage comments')) {
                App::abort(403, __('Insufficient User Rights.'));
            }

            $comment = Comment::find($id);

            if (!$comment) {
                App::abort(404, __('Comment not found.'));
            }

        }

        unset($data['created']);

        // check minimum idle time in between user comments
        if (!$this->user->hasAccess('blog: skip comment min idle')
            and $minidle = $this->blog->config('comments.minidle')
            and $commentIdle = Comment::where($this->user->isAuthenticated() ? ['user_id' => $this->user->id] : ['ip' => App::request()->getClientIp()])->orderBy('created', 'DESC')->first()
        ) {

            $diff = $commentIdle->created->diff(new \DateTime("- {$minidle} sec"));

            if ($diff->invert) {
                App::abort(403, __('Please wait another %seconds% seconds before commenting again.', ['%seconds%' => $diff->s + $diff->i * 60 + $diff->h * 3600]));
            }
        }

        if (@$data['parent_id'] && !$parent = Comment::find((int) $data['parent_id'])) {
            App::abort(404, __('Parent not found.'));
        }

        if (!@$data['post_id'] || !$post = Post::where(['id' => $data['post_id']])->first() or !($this->user->hasAccess('blog: manage comments') || $post->isCommentable() && $post->isPublished())) {
            App::abort(404, __('Post not found.'));
        }

        $approved_once = (boolean) Comment::where(['user_id' => $this->user->id, 'status' => Comment::STATUS_APPROVED])->first();
        $comment->status = $this->user->hasAccess('blog: skip comment approval') ? Comment::STATUS_APPROVED : ($this->user->hasAccess('blog: comment approval required once') && $approved_once ? Comment::STATUS_APPROVED : Comment::STATUS_PENDING);

        // check the max links rule
        if ($comment->status == Comment::STATUS_APPROVED && $this->blog->config('comments.maxlinks') <= preg_match_all('/<a [^>]*href/i', @$data['content'])) {
            $comment->status = Comment::STATUS_PENDING;
        }

        // check for spam
        //App::trigger('system.comment.spam_check', new CommentEvent($comment));

        $comment->save($data);

        return ['message' => 'success', 'comment' => $comment];
    }

    /**
     * @Access("blog: manage comments")
     * @Route("/{id}", methods="DELETE", requirements={"id"="\d+"})
     * @Request({"id": "int"}, csrf=true)
     */
    public function deleteAction($id)
    {
        if ($comment = Comment::find($id)) {
            $comment->delete();
        }

        return ['message' => 'success'];
    }

    /**
     * @Access("blog: manage comments")
     * @Route("/bulk", methods="POST")
     * @Request({"comments": "array"}, csrf=true)
     */
    public function bulkSaveAction($comments = [])
    {

        foreach ($comments as $data) {
            $this->saveAction($data, isset($data['id']) ? intval($data['id']) : 0);
        }

        return ['message' => 'success'];
    }

    /**
     * @Access("blog: manage comments")
     * @Route("/bulk", methods="DELETE")
     * @Request({"ids": "array"}, csrf=true)
     */
    public function bulkDeleteAction($ids = [])
    {
        foreach (array_filter($ids) as $id) {
            $this->deleteAction($id);
        }

        return ['message' => 'success'];
    }
}
