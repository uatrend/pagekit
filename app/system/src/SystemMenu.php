<?php

namespace Pagekit\System;

use Pagekit\Application as App;
use Pagekit\Util\ArrObject;

class SystemMenu implements \IteratorAggregate, \JsonSerializable
{
    protected array $items = [];

    /**
     * Gets all menu items.
     */
    public function getItems(): array
    {
        foreach ($this->items as $item) {

            if ($item['active'] !== true) {
                continue;
            }

            while ($item = $this->getItem($item['parent'])) {
                $item['active'] = true;
            }
        }

        return $this->items;
    }

    /**
     * Gets a menu item.
     *
     * @param string $id
     */
    public function getItem($id)
    {
        return isset($this->items[$id]) ? $this->items[$id] : null;
    }

    /**
     * Adds a menu item.
     *
     * @param string $id
     * @param array  $item
     */
    public function addItem($id, array $item): void
    {
        $meta  = App::user()->get('admin.menu', []);
        $route = App::request()->attributes->get('_route');

        $item = new ArrObject($item, [
            'id' => $id,
            'label' => $id,
            'parent' => 'root',
            'priority' => 0
        ]);

        if (!App::user()->hasAccess($item['access'])) {
            return;
        }

        if (isset($meta[$id])) {
            $item['priority'] = $meta[$id];
        }

        if ($item['icon']) {
            $item['icon'] = App::url()->getStatic($item['icon']);
        }

        $item['active'] = (bool) preg_match('#^'.str_replace('*', '.*', $item['active'] ?: $item['url']).'$#', $route);
        $item['url'] = App::url($item['url']);

        $this->items[$id] = $item;
    }

    /**
     * Implements the IteratorAggregate.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->getItems());
    }

    /**
     * Implements JsonSerializable interface.
     */
    public function jsonSerialize(): array
    {
        return $this->getItems();
    }
}
