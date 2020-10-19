<?php

use Pagekit\Markdown\Markdown;

return [

    'name' => 'markdown',

    'main' => function ($app) {

        $app['markdown'] = fn() => new Markdown;

    },

    'autoload' => [

        'Pagekit\\Markdown\\' => 'src'

    ]

];
