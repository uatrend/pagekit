<?php

namespace Pagekit\Dashboard;

use Pagekit\Application as App;
use Pagekit\Module\Module;

class DashboardModule extends Module
{
    /**
     * Gets a widget.
     *
     * @param  string $id
     */
    public function getWidget($id): array
    {
        $widgets = $this->getWidgets();

        return isset($widgets[$id]) ? $widgets[$id] : null;
    }

    /**
     * Gets all user widgets.
     */
    public function getWidgets(): array
    {
        return App::config()->get('system/dashboard', $this->config('defaults'))->toArray();
    }

    /**
     * Save widgets on user.
     *
     * @param array $widgets
     */
    public function saveWidgets(array $widgets): void
    {
        App::config()->set('system/dashboard', $widgets);
    }
}
