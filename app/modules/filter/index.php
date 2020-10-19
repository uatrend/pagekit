<?php

use Pagekit\Filter\FilterManager;

return [

    'name' => 'filter',

    'main' => function ($app) {

        $app['filter'] = fn() => new FilterManager($this->config['defaults']);

    },

    'autoload' => [

        'Pagekit\\Filter\\' => 'src'

    ],

    'config' => [

        'defaults' => null

    ]
];
