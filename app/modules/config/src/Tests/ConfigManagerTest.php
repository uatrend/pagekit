<?php

namespace Pagekit\Config\Tests;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Cache\ArrayCache;
use Pagekit\Config\ConfigManager;

class ConfigManagerTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testGet(): void
    {
    }

    // public function testGet()
    // {
    //     $connection = $this->getConnection();
    //     $connection->expects($this->once())
    //                ->method('fetchAssoc')
    //                ->will($this->returnValue(['value' => json_encode('bar')]));

    //     $options = $this->getConfig($connection, $cache = $this->getCache());

    //     // get from database
    //     $this->assertEquals('bar', $options->get('foo'));

    //     // check cached value
    //     $this->assertTrue($cache->contains('Options:foo'));

    //     // get from cache
    //     $options = $this->getConfig($connection, $cache);
    //     $this->assertEquals('bar', $options->get('foo'));
    // }

    // public function testGetWithAutoload()
    // {
    //     $connection = $this->getConnection();
    //     $connection->expects($this->once())
    //                ->method('fetchAll')
    //                ->will($this->returnValue([['name' => 'foo', 'value' => json_encode('bar'), 'autoload' => 1]]));

    //     $options = $this->getConfig($connection, $cache = $this->getCache());

    //     // get from database
    //     $this->assertEquals('bar', $options->get('foo'));

    //     // check cached value
    //     $this->assertTrue($cache->contains('Options:Autoload'));

    //     // get from cache
    //     $options = $this->getConfig($connection, $cache);
    //     $this->assertEquals('bar', $options->get('foo'));
    // }

    // public function testGetIgnored()
    // {
    //     $connection = $this->getConnection();
    //     $options = $this->getConfig($connection, $cache = $this->getCache());
    //     $cache->save('Options.Ignore', ['Ignored' => 'value']);

    //     $this->assertEquals(null, $options->get('Ignored'));

    // }

    // public function testGetEmptyOptionName()
    // {
    //     $this->expectException(\InvalidArgumentException::class);
    //     $options = $this->getConfig();
    //     $options->get(null);
    // }

    // public function testSet()
    // {
    //     $connection = $this->getConnection();
    //     $connection->expects($this->once())
    //                ->method('executeQuery')
    //                ->will($this->returnValue(1));
    //     $connection->expects($this->once())
    //                ->method('getDatabasePlatform')
    //                ->will($this->returnCallback(function() {
    //                     return new MySqlPlatform;
    //                }));

    //     $options = $this->getConfig($connection);
    //     $options->set('foo', 'bar');

    //     $this->assertEquals('bar', $options->get('foo'));
    // }

    // public function testSetWithAutoload()
    // {
    //     $connection = $this->getConnection();
    //     $connection->expects($this->once())
    //                ->method('executeQuery')
    //                ->will($this->returnValue(1));
    //     $connection->expects($this->once())
    //                ->method('getDatabasePlatform')
    //                ->will($this->returnCallback(function() {
    //                     return new MySqlPlatform;
    //                }));

    //     $options = $this->getConfig($connection);
    //     $options->set('foo', 'bar', true);

    //     $this->assertEquals('bar', $options->get('foo'));
    // }

    // public function testSetEmptyOptionName()
    // {
    //     $this->expectException(\InvalidArgumentException::class);
    //     $options = $this->getConfig();
    //     $options->set(null, null);
    // }

    // public function testSetProtectedOption()
    // {
    //     $this->expectException(\InvalidArgumentException::class);
    //     $options = $this->getConfig();
    //     $options->set('Autoload', null);
    // }

    // public function testRemove()
    // {
    //     $connection = $this->getConnection();
    //     $connection->expects($this->once())
    //                ->method('delete')
    //                ->will($this->returnValue(1));

    //     $options = $this->getConfig($connection);
    //     $options->set('foo', 'bar');

    //     $this->assertEquals('bar', $options->get('foo'));

    //     $options->remove('foo');

    //     $this->assertNull($options->get('foo'));
    // }

    // public function testRemoveEmptyOptionName()
    // {
    //     $this->expectException(\InvalidArgumentException::class);
    //     $options = $this->getConfig();
    //     $options->remove(null);
    // }

    // public function testRemoveProtectedOption()
    // {
    //     $this->expectException(\InvalidArgumentException::class);
    //     $options = $this->getConfig();
    //     $options->remove('Autoload');
    // }

    protected function getConnection()
    {
        $mock = $this
            ->getMockBuilder('Pagekit\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'fetchAssoc',
                    'fetchAll',
                    'executeQuery',
                    'getDatabasePlatform',
                    'update',
                    'insert',
                    'delete',
                    'isConnected',
                ]
            )
            ->getMock();

        $mock->method('isConnected')
             ->will($this->returnValue(true));

        return $mock;
    }

    protected function getCache(): ArrayCache
    {
        return new ArrayCache();
    }

    protected function getConfig($connection = null, $cache = null): ConfigManager
    {
        $connection = $connection ?: $this->getConnection();
        $cache = $cache ?: $this->getCache();

        return new ConfigManager($connection, $cache, 'test');
    }
}
