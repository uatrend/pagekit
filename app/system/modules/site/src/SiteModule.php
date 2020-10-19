<?php

namespace Pagekit\Site;

use Pagekit\Application as App;
use Pagekit\Module\Module;
use Pagekit\Site\Model\Node;

class SiteModule extends Module
{
    protected ?array $types = null;

    /**
     * {@inheritdoc}
     */
    public function main(App $app): void
    {
        $app['node'] = function ($app) {

            if ($id = $app['request']->attributes->get('_node') and $node = Node::find($id, true)) {
                return $node;
            }

            return Node::create();
        };

        $app['menu'] = function ($app) {

            $menus = new MenuManager($app->config($app['theme']->name), $this->config('menus'));

            foreach ($app['theme']->get('menus', []) as $name => $label) {
                $menus->register($name, $label);
            }

            return $menus;
        };

    }

    /**
     * @param  string $type
     */
    public function getType($type): ?array
    {
        $types = $this->getTypes();

        return isset($types[$type]) ? $types[$type] : null;
    }

    public function getTypes(): ?array
    {
        if (!$this->types) {

            foreach (App::module() as $module) {
                foreach ((array) $module->get('nodes') as $type => $route) {
                    $this->registerType($type, $route);
                }
            }

            $this->registerType('link', ['label' => 'Link', 'frontpage' => false]);

            App::trigger('site.types', [$this]);
        }

        return $this->types;
    }

    /**
     * Register a node type.
     *
     * @param string $type
     * @param array  $route
     */
    public function registerType($type, array $route): void
    {
        if (isset($route['protected']) and $route['protected'] and !array_filter(Node::findAll(true), fn($node) => $type === $node->type)) {
            Node::create([
                'title' => $route['label'],
                'slug' => App::filter($route['label'], 'slugify'),
                'type' => $type,
                'status' => 1,
                'link' => $route['name']
            ])->save();
        }

        $route['id'] = $type;
        $this->types[$type] = $route;
    }
}
