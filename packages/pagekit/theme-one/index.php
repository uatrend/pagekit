<?php

return [

    'name' => 'theme-one',

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

        'navbar' => 'Navbar',
        'hero' => 'Hero',
        'top' => 'Top',
        'sidebar' => 'Sidebar',
        'bottom' => 'Bottom',
        'footer' => 'Footer',
        'offcanvas' => 'Offcanvas'

    ],

    /**
     * Node defaults
     */
    'node' => [

        'title_hide' => false,
        'title_large' => false,
        'alignment' => '',
        'html_class' => '',
        'sidebar_first' => false,
        'hero_image' => '',
        'hero_viewport' => '',
        'hero_contrast' => '',
        'hero_parallax' => '',
        'navbar_transparent' => '',
        'top_style' => 'uk-section-muted',
        'main_style' => 'uk-section-default',
        'bottom_style' => 'uk-section-muted'

    ],

    /**
     * Widget defaults
     */
    'widget' => [

        'title_hide' => false,
        'title_size' => 'uk-card-title',
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
        'logo_offcanvas' => ''

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

            $classes = [
                // 'navbar' => 'tm-navbar',
                'hero' => '',
                'parallax' => ''
            ];

            $sticky = [
                'media' => 768,
                'show-on-up' => 1,
                'animation' => 'uk-animation-slide-top',
                'cls-active' => 'uk-navbar-sticky',
                'sel-target' => '.uk-navbar-container'
            ];

            $sticky_args = "";
            array_walk($sticky, function ($item, $key) use (&$sticky_args) { $sticky_args .= $key .": " . $item . "; "; } );

            if ($params['hero_viewport']) {
                $classes['viewport'] = 'uk-height-viewport="offset-top: true"';
            }

            // Sticky overlay navbar if hero position exists
            if ($params['navbar_transparent'] && $view->position()->exists('hero') && $params['hero_image']) {

                $classes['header_transparent'] = 'tm-header-transparent="dark"';

                if ($params['hero_viewport']) {
                    $classes['viewport'] = 'uk-height-viewport';
                } else {
                    $classes['hero'] = 'tm-hero-padding';
                }

                if ($params['hero_contrast']) {
                    $classes['header_transparent'] = 'tm-header-transparent="light"';
                }

            }

            if ($params['hero_parallax'] && $view->position()->exists('hero') && $params['hero_image']) {
                $classes['parallax'] = 'uk-parallax="bgy: -400"';
            }

            if ($params['hero_contrast'] && $params['hero_image']) {
                $classes['hero'] .= ' uk-section-secondary';
            }

            $classes['sticky'] = 'uk-sticky="'. $sticky_args .'"';

            $params['classes'] = $classes;
        },

        'view.system/site/widget-menu' => function ($event, $view) {

            if ($event['widget']->position == 'navbar') {
                $event->setTemplate('menu-navbar.php');
            }

        }

    ]

];
