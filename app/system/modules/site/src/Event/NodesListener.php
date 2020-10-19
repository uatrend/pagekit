<?php

namespace Pagekit\Site\Event;

use Pagekit\Application as App;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\Site\Model\Node;

class NodesListener implements EventSubscriberInterface
{
    /**
     * Registers node routes
     */
    public function onRequest(): void
    {
        $site      = App::module('system/site');
        $frontpage = $site->config('frontpage');
        $nodes     = Node::findAll(true);

        uasort($nodes, fn($a, $b) => strcmp(substr_count($a->path, '/'), substr_count($b->path, '/')) * -1);

        foreach ($nodes as $node) {

            if ($node->status !== 1 || !$type = $site->getType($node->type)) {
                continue;
            }

            $type             = array_replace(['alias' => '', 'redirect' => '', 'controller' => ''], $type);
            $type['defaults'] = array_merge(isset($type['defaults']) ? $type['defaults'] : [], $node->get('defaults', []), ['_node' => $node->id]);
            $type['path']     = $node->path;

            $route = null;
            if ($node->get('alias')) {
                App::routes()->alias($node->path, $node->link, $type['defaults']);
            } elseif ($node->get('redirect')) {
                App::routes()->redirect($node->path, $node->get('redirect'), $type['defaults']);
            } elseif ($type['controller']) {
                App::routes()->add($type);
            }

            if (!$frontpage && isset($type['frontpage']) && $type['frontpage']) {
                $frontpage = $node->id;
            }

        }

        if ($frontpage && isset($nodes[$frontpage])) {
            App::routes()->alias('/', $nodes[$frontpage]->link);
        } else {
            App::routes()->get('/', fn() => __('No Frontpage assigned.'));
        }
    }

    public function onNodeInit($event, $node): void
    {
        if ('link' === $node->type && $node->get('redirect')) {
            $node->link = $node->path;
        }
    }

    public function onRoleDelete($event, $role): void
    {
        Node::removeRole($role);
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(): array
    {
        return [
            'request' => ['onRequest', 110],
            'model.node.init' => 'onNodeInit',
            'model.role.deleted' => 'onRoleDelete'
        ];
    }
}
