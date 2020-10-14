<?php

use Pagekit\Application as App;

return [

    'name' => 'theme-one',

    'main' => function($app) {

        if ($app->isAdmin()) {
            return;
        }

        require __DIR__.'/functions.php';
    },

    /**
     * Menu positions
     */
    'menus' => [

        'main' => 'Main',
        'offcanvas' => 'Offcanvas'

    ],

    /**
     * Widget positions
     */
    'positions' => [

        'header' => 'Header',
        'navbar' => 'Navbar',
        'hero' => 'Hero',
        'top-a' => 'Top A',
        'top-b' => 'Top B',
        'top-c' => 'Top C',
        'sidebar' => 'Sidebar',
        'bottom-a' => 'Bottom A',
        'bottom-b' => 'Bottom B',
        'bottom-c' => 'Bottom C',
        'footer' => 'Footer',
        'offcanvas' => 'Offcanvas'

    ],

    /**
     * Node defaults
     */
    'node' => [
        // Predefinite params for all nodes.
        'title_hide' => false,
        'title_large' => false,
        'alignment' => '',
        'html_class' => '',
        'content_hide' => false,
        'sidebar_first' => false,
        'positions' => [
            'hero' => [
                'height' => 'full',
                'style' => 'uk-section-secondary',
                'size'  => 'uk-section-large',
            ],
            'top-a' => [
                'style' => 'uk-section-muted',
            ],
            'top-c' => [
                'style' => 'uk-section-muted',
            ],
            'bottom-a' => [
                'style' => 'uk-section-muted',
            ],
            'bottom-c' => [
                'style' => 'uk-section-muted',
            ]
        ],


    ],

    /**
     * Position defaults
     */
    'position' => [
        // Order is important, choose position to edit default options.
        'customizable' => ['hero', 'top-a', 'top-b', 'top-c', 'main', 'bottom-a', 'bottom-b', 'bottom-c'],
        'defaults' => [
            'image' => '',
            'image_position' => '',
            'effect' => '',
            'width' => '',
            'height' => '',
            'vertical_align' => 'middle',
            'style' => 'uk-section-default',
            'size'  => '',
            'padding_remove_top' => false,
            'padding_remove_bottom' => false,
            'preserve_color' => false,
            'overlap' => false,
            'header_transparent' => false,
            'header_preserve_color' => false,
            'header_transparent_noplaceholder' => false
        ]

    ],

    /**
     * Widget defaults
     */
    'widget' => [

        'title_hide' => false,
        'title_size' => 'uk-h3',
        'alignment' => '',
        'html_class' => '',
        'panel' => ''

    ],

    /**
     * Settings url
     */
    'settings' => '@site/settings#site-theme',

    /**
     * Configuration defaults
     */
    'config' => [

        'logo_contrast' => '',
        'logo_offcanvas' => '',
        'header' => [
            'layout' => 'horizontal-right',
            'fullwidth' => false,
            'logo_padding_remove' => false
        ],
        'navbar' => [
            'sticky' => 1,
            'dropbar' => '',
            'dropbar_align' => 'left',
            'dropdown_boundary' => false,
            'offcanvas' => [
                'mode' => 'reveal',
                'overlay' => false,
                'flip' => false
            ]
        ]

    ],

    /**
     * Events
     */
    'events' => [

        'view.system/site/admin/settings' => function ($event, $view) use ($app) {
            $view->script('site-theme', 'theme:app/bundle/site-theme.js', 'site-settings');
            $view->data('$theme', $this);
        },

        'view.system/site/admin/edit' => function ($event, $view) {
            $view->script('node-theme', 'theme:app/bundle/node-theme.js', 'site-edit');
            $view->data('$theme', $this->options);
        },

        'view.system/widget/edit' => function ($event, $view) {
            $view->script('widget-theme', 'theme:app/bundle/widget-theme.js', 'widget-edit');
        },

        /**
         * Custom markup calculations based on theme settings
         */
        'view.layout' => function ($event, $view) use ($app) {

            if ($app->isAdmin()) {
                return;
            }

            $params = $view->params;
            $params['position'] = $this->options['position'];
        },

        'view.system/site/widget-menu' => function ($event, $view) {

            if ($event['widget']->position == 'navbar') {
                $event->setTemplate('menu-navbar.php');
            }

        }

    ]

];
