<?php

namespace Pagekit\Installer\Helper;

use Composer\Config;
use Composer\Factory as BaseFactory;
use Composer\IO\IOInterface;

class Factory extends BaseFactory
{
    protected static array $config = [];

    public static function bootstrap($config): void
    {
        self::$config = $config;
    }

    public static function createConfig(IOInterface $io = null, $cwd = null): Config
    {
        $config = new Config(true, $cwd);
        $config->merge(['config' => static::$config]);

        return $config;
    }
}
