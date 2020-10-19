<?php

use Pagekit\Feed\FeedFactory;

return [

    'name' => 'feed',

    'main' => function ($app) {

        $app['feed'] = fn() => new FeedFactory;

    },

    'autoload' => [

        'Pagekit\\Feed\\' => 'src'

    ]

];
