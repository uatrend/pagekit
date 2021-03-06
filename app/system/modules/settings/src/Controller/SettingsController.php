<?php

namespace Pagekit\System\Controller;

use Pagekit\Application as App;
use Pagekit\Config\Config;

/**
 * @Access("system: access settings", admin=true)
 */
class SettingsController
{
    public function indexAction(): array
    {
        return [
            '$view' => [
                'title' => __('Settings'),
                'name'  => 'system:modules/settings/views/settings.php'
            ]
        ];
    }

    /**
     * @Request({"config": "array", "options": "array"}, csrf=true)
     */
    public function saveAction($values = [], $options = []): array
    {
        $config = new Config;
        $config->merge(include $file = App::get('config.file'));

        foreach ($values as $module => $value) {
            $config->set($module, $value);
        }

        file_put_contents($file, $config->dump());

        foreach ($options as $module => $value) {
            $this->configAction($module, $value);
        }

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file);
        }

        return ['message' => 'success'];
    }

    /**
     * @Request({"name", "config": "array"}, csrf=true)
     */
    public function configAction($name, $config = []): array
    {
        App::config()->set($name, array_replace(App::config($name)->toArray(), $config));

        return ['message' => 'success'];
    }
}
