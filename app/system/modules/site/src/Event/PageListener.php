<?php

namespace Pagekit\Site\Event;

use Pagekit\Application as App;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\Site\Model\Page;

class PageListener implements EventSubscriberInterface
{
    public function onNodeSave($event, $request): void
    {
        if (null === $node = $request->get('node')
            or null === $data = $request->get('page')
            or 'page' !== @$node['type']
        ) {
            return;
        }

        $page = $this->getPage(@$node['id']);
        $page->save($data);

        $node['data']['defaults'] = ['id' => $page->id];
        $node['link'] = '@page/'.$page->id;

        $request->request->set('node', $node);
    }

    public function onNodeDeleted($event, $node): void
    {
        if ('page' !== $node->type) {
            return;
        }

        $page = $this->getPage($node->get('defaults.id', 0));

        if ($page->id) {
            $page->delete();
        }
    }

    public function onRouteConfigure($event, $route, $routes): void
    {
        if ($route->getName() === '@page') {
            $routes->remove('@page');
            $route->setName('@page/'.$route->getDefault('id'));
            $routes->add($route->getName(), $route);
            // Custom Symfony 4
            $route->setOption('utf8', true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(): array
    {
        return [
            'before@site/api/node/save' => 'onNodeSave',
            'before@site/api/node/save_1' => 'onNodeSave',
            'model.node.deleted' => 'onNodeDeleted',
            'route.configure' => 'onRouteConfigure'
        ];
    }

    /**
     * Find page entity by node.
     *
     * @param  int $id
     */
    protected function getPage($id): Page
    {
        if (!$id or !$page = Page::find($id)) {
            $page = Page::create();
        }

        return $page;
    }
}
