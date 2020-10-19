<?php

namespace Pagekit\User\Event;

use Pagekit\Application as App;
use Pagekit\Auth\Event\LoginEvent;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\User\Model\User;

class UserListener implements EventSubscriberInterface
{
    /**
     * Updates user's last login time
     */
    public function onUserLogin(LoginEvent $event): void
    {
        User::updateLogin($event->getUser());
    }

    public function onRoleDelete($event, $role): void
    {
        User::removeRole($role);
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(): array
    {
        return [
            'auth.login' => 'onUserLogin',
            'model.role.deleted' => 'onRoleDelete'
        ];
    }
}
