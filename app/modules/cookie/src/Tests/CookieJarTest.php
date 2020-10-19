<?php

namespace Pagekit\Cookie\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Cookie\CookieJar;
use Symfony\Component\HttpFoundation\Cookie;

class CookieJarTest extends TestCase
{
    protected ?Cookie $cookie = null;

    protected ?CookieJar $cookieJar = null;

	public function setUp(): void
	{
		$this->cookieJar = new CookieJar();
	}

	public function testSet(): void {
		$this->cookie = $this->cookieJar->set('testCookie', 'testValue');
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\Cookie', $this->cookie);
		$this->assertEquals('testValue', $this->cookie->getValue());
	}

	public function testHasGet(): void {
		$this->cookieJar->set('testCookie', 'testValue');
		$this->assertTrue($this->cookieJar->has('testCookie'));
		$this->assertEquals('testValue', $this->cookieJar->get('testCookie')->getValue());
	}

	public function testRemove(): void {
		$this->cookieJar->set('testCookie', 'testValue');
		$this->cookie = $this->cookieJar->remove('testCookie');
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\Cookie', $this->cookie);
		$this->assertTrue($this->cookie->isCleared());
	}

	public function testGetQueuedCookies(): void {
		$this->cookieJar->set('cookie1', 'value1');
		$this->cookieJar->set('cookie2', 'value2');
		$this->assertCount(2, $this->cookieJar->getQueuedCookies());
	}
}
