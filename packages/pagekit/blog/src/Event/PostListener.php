<?php

namespace Pagekit\Blog\Event;

use Pagekit\Blog\Model\Post;
use Pagekit\Comment\Model\Comment;
use Pagekit\Event\EventSubscriberInterface;

class PostListener implements EventSubscriberInterface
{
    public function onCommentChange($event, Comment $comment): void
    {
        Post::updateCommentInfo($comment->post_id);
    }

    public function onRoleDelete($event, $role): void
    {
        Post::removeRole($role);
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(): array
    {
        return [
            'model.comment.saved' => 'onCommentChange',
            'model.comment.deleted' => 'onCommentChange',
            'model.role.deleted' => 'onRoleDelete'
        ];
    }
}
