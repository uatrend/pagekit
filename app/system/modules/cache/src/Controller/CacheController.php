<?php

namespace Pagekit\Cache\Controller;

use Pagekit\Application as App;

/**
 * @Access(admin=true)
 */
class CacheController
{
    /**
     * @Request({"caches": "array"}, csrf=true)
     */
    public function clearAction($caches): array
    {
        App::module('system/cache')->clearCache($caches);

        return ['message' => 'success'];
    }
}
