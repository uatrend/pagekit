<?php

namespace Pagekit\Blog\Controller;

use Pagekit\Application as App;
use Pagekit\Blog\Model\Post;
use Pagekit\Module\Module;

class SiteController
{
    /**
     * @var Module
     */
    protected $blog;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->blog = App::module('blog');
    }

    /**
     * @Route("/")
     * @Route("/page/{page}", name="page", requirements={"page" = "\d+"})
     */
    public function indexAction($page = 1)
    {
        $query = Post::where(['status = ?', 'date < ?'], [Post::STATUS_PUBLISHED, new \DateTime])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->related('user');

        if (!$limit = $this->blog->config('posts.posts_per_page')) {
            $limit = 10;
        }

        $count = $query->count('id');
        $total = ceil($count / $limit);
        $page = max(1, min($total, $page));

        $query->offset(($page - 1) * $limit)->limit($limit)->orderBy('date', 'DESC');

        foreach ($posts = $query->get() as $post) {
            $post->excerpt = App::content()->applyPlugins($post->excerpt, ['post' => $post, 'markdown' => $post->get('markdown')]);
            $post->content = App::content()->applyPlugins($post->content, ['post' => $post, 'markdown' => $post->get('markdown'), 'readmore' => true]);
        }

        return [
            '$view' => [
                'title' => __('Blog'),
                'name' => 'blog/posts.php',
                'link:feed' => [
                    'rel' => 'alternate',
                    'href' => App::url('@blog/feed'),
                    'title' => App::module('system/site')->config('title'),
                    'type' => App::feed()->create($this->blog->config('feed.type'))->getMIMEType()
                ]
            ],
            'blog' => $this->blog,
            'posts' => $posts,
            'total' => $total,
            'page' => $page
        ];
    }

    /**
     * @Route("/feed")
     * @Route("/feed/{type}")
     */
    public function feedAction($type = '')
    {
        // fetch locale and convert to ISO-639 (en_US -> en-us)
        $locale = App::module('system')->config('site.locale');
        $locale = str_replace('_', '-', strtolower($locale));

        $site = App::module('system/site');
        $feed = App::feed()->create($type ?: $this->blog->config('feed.type'), [
            'title' => $site->config('title'),
            'link' => App::url('@blog', [], 0),
            'description' => $site->config('description'),
            'element' => ['language', $locale],
            'selfLink' => App::url('@blog/feed', [], 0)
        ]);

        if ($last = Post::where(['status = ?', 'date < ?'], [Post::STATUS_PUBLISHED, new \DateTime])->limit(1)->orderBy('modified', 'DESC')->first()) {
            $feed->setDate($last->modified);
        }

        foreach (Post::where(['status = ?', 'date < ?'], [Post::STATUS_PUBLISHED, new \DateTime])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->related('user')->limit($this->blog->config('feed.limit'))->orderBy('date', 'DESC')->get() as $post) {
            $url = App::url('@blog/id', ['id' => $post->id], 0);
            $feed->addItem(
                $feed->createItem([
                    'title' => $post->title,
                    'link' => $url,
                    'description' => App::content()->applyPlugins($post->content, ['post' => $post, 'markdown' => $post->get('markdown'), 'readmore' => true]),
                    'date' => $post->date,
                    'author' => [$post->user->name, $post->user->email],
                    'id' => $url
                ])
            );
        }

        return App::response($feed->output(), 200, ['Content-Type' => $feed->getMIMEType().'; charset='.$feed->getEncoding()]);
    }

    /**
     * @Route("/{id}", name="id")
     * @Captcha(route="@blog/api/comment/save")
     * @Captcha(route="@blog/api/comment/save_1")
     */
    public function postAction($id = 0)
    {
        if (!$post = Post::where(['id = ?', 'status = ?', 'date < ?'], [$id, Post::STATUS_PUBLISHED, new \DateTime])->related('user')->first()) {
            App::abort(404, __('Post not found!'));
        }

        if (!$post->hasAccess(App::user())) {
            App::abort(403, __('Insufficient User Rights.'));
        }

        $post->excerpt = App::content()->applyPlugins($post->excerpt, ['post' => $post, 'markdown' => $post->get('markdown')]);
        $post->content = App::content()->applyPlugins($post->content, ['post' => $post, 'markdown' => $post->get('markdown')]);

        $user = App::user();

        $description = $post->get('meta.og:description');
        if (!$description) {
            $description = strip_tags($post->excerpt ?: $post->content);
            $description = rtrim(mb_substr($description, 0, 150), " \t\n\r\0\x0B.,") . '...';
        }

        return [
            '$view' => [
                'title' => __($post->title),
                'name' => 'blog/post.php',
                'og:type' => 'article',
                'article:published_time' => $post->date->format(\DateTime::ATOM),
                'article:modified_time' => $post->modified->format(\DateTime::ATOM),
                'article:author' => $post->user->name,
                'og:title' => $post->get('meta.og:title') ?: $post->title,
                'og:description' => $description,
                'og:image' =>  $post->get('image.src') ? App::url()->getStatic($post->get('image.src'), [], 0) : false
            ],
            '$comments' => [
                'config' => [
                    'post' => $post->id,
                    'enabled' => $post->isCommentable(),
                    'requireinfo' => $this->blog->config('comments.require_email'),
                    'max_depth' => $this->blog->config('comments.max_depth'),
                    'user' => [
                        'name' => $user->name,
                        'isAuthenticated' => $user->isAuthenticated(),
                        'canComment' => $user->hasAccess('blog: post comments'),
                        'skipApproval' => $user->hasAccess('blog: skip comment approval')
                    ]
                ]
            ],
            'blog' => $this->blog,
            'post' => $post
        ];
    }
}
