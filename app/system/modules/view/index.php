<?php

use Pagekit\Util\ArrObject;
use Pagekit\View\Event\ResponseListener;

return [

    'name' => 'system/view',

    'main' => function ($app) {

        $app->extend('twig', function ($twig) use ($app) {

            $twig->addFilter(new Twig_SimpleFilter('trans', '__'));
            $twig->addFilter(new Twig_SimpleFilter('transChoice', '_c'));

            return $twig;

        });

        $app->extend('assets', function ($assets) use ($app) {

            $assets->register('file', 'Pagekit\View\Asset\FileLocatorAsset');

            return $assets;
        });

    },

    'autoload' => [

        'Pagekit\\View\\' => 'src'

    ],

    'events' => [

        'boot' => function ($event, $app) {
            $app->subscribe(new ResponseListener());
        },

        'site' => function ($event, $app) {
            $app->on('view.meta', function ($event, $meta) use ($app) {
                $meta->add('canonical', $app['url']->get($app['request']->attributes->get('_route'), $app['request']->attributes->get('_route_params', []), 0));
            }, 60);
        },

        'view.init' => [function ($event, $view) {
            $view->defer('head');
            $view->meta(['generator' => 'Pagekit']);
            $view->addGlobal('params', new ArrObject());
        }, 20],

        'view.data' => function ($event, $data) use ($app) {
            $data->add('$pagekit', [
                'url' => $app['router']->getContext()->getBaseUrl(),
                'csrf' => $app['csrf']->generate()
            ]);
        },

        'view.styles' => function ($event, $styles) {
            $styles->register('codemirror-hint', 'app/system/modules/editor/app/assets/codemirror/show-hint.css');
            $styles->register('codemirror', 'app/system/modules/editor/app/assets/codemirror/codemirror.css', ['codemirror-hint']);
        },

        'view.scripts' => function ($event, $scripts) use ($app) {
            $scripts->register('codemirror', 'app/system/modules/editor/app/assets/codemirror/codemirror.min.js');
            $scripts->register('marked', 'app/system/modules/editor/app/assets/marked/marked.min.js');
            $scripts->register('lodash', 'app/assets/lodash/dist/'  . ($app->debug() ? 'lodash.js' : 'lodash.min.js'));
            $scripts->register('vue', 'app/system/app/bundle/vue.js', ['uikit', 'uikit-icons', 'vue-dist', 'lodash', 'locale']);
            $scripts->register('vue-dist', 'app/assets/vue/dist/' . ($app->debug() ? 'vue.js' : 'vue.min.js'));
            $scripts->register('locale', $app->url('@system/intl', ['locale' => $app->module('system/intl')->getLocale(), 'v' => $scripts->getFactory()->getVersion()]), [], ['type' => 'url']);
            $scripts->register('uikit', 'app/assets/uikit/dist/js/' . ($app->debug() ? 'uikit.js' : 'uikit.min.js'));
            $scripts->register('uikit-icons', 'app/assets/uikit/dist/js/' . ($app->debug() ? 'uikit-icons.js' : 'uikit-icons.min.js'), 'uikit');
            $scripts->register('theme-admin', 'system/theme:js/theme.js', ['vue']);
        }

    ]

];
