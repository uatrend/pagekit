<?php

return [

    'name' => 'system/text',

    'label' => 'Text',

    'render' => fn($widget) => $app['view']->render('system/site/widget-text.php', compact('widget')),

    'events' => [

        'view.scripts' => function ($event, $scripts) {
            $scripts->register('widget-text', 'system/site:app/bundle/widget-text.js', '~widgets');
        }

    ]

];
