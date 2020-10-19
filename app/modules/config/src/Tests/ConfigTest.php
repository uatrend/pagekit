<?php

namespace Pagekit\Config\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Config\Config;
use Pagekit\Config\Loader\PhpLoader;

class ConfigTest extends TestCase
{
    protected ?Config $config = null;

    protected ?array $values = null;

    public function setUp(): void
	{
		$values = [
	    	'foo' => [
	    		'bar' => 'test'
	    	]
	    ];

	    $this->config = new Config($values);
	    $this->values = $values;
	}

	public function testHas(): void
	{
		$this->assertTrue($this->config->has('foo'));
		$this->assertTrue(!$this->config->has('none'));
	}

	public function testGet(): void
	{
		$this->assertEquals($this->config->get('foo.bar'), 'test');
	}

	public function testToArray(): void
	{
		$this->assertEquals($this->config->toArray(), $this->values);
	}

	public function testSet(): void
	{
		$this->config->set('foo.bar2', 'test2');
		$this->assertEquals($this->config->get('foo.bar2'), 'test2');
		$this->config->offsetSet('foo.bar', 'test3');
		$this->assertTrue($this->config->offsetExists('foo.bar'));
		$this->assertEquals($this->config->offsetGet('foo.bar'), 'test3');
		$this->config->offsetUnset('foo.bar');
		$this->assertEquals($this->config->offsetGet('foo.bar'), null);
	}

	public function testDump(): void
	{
		$this->assertIsString($this->config->dump());
	}
}
