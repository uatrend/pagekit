<?php

namespace Pagekit\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filesystem\Filesystem;
use Pagekit\Filesystem\Locator;

class LocatorTest extends TestCase
{
    protected ?Filesystem $file = null;
    protected ?Locator $locator = null;

    public function setUp(): void
    {
        $this->file    = new Filesystem;
        $this->locator = new Locator(__DIR__);
    }

    /**
     * @dataProvider dataGetPaths
     */
    public function testGet($path, $result, $exists): void
    {
        $this->assertSame($exists, $this->file->exists($result));
        $this->assertSame($result, $this->locator->get($path));
    }

    public function dataGetPaths(): array
    {
        $fixtures = __DIR__.'/Fixtures';

        return [
            ['Fixtures', $fixtures, true],
            ['/Fixtures', $fixtures, true],
            ['Fixtures/file1.txt', $fixtures.'/file1.txt', true],
            ['/Fixtures/file1.txt', $fixtures.'/file1.txt', true],
            ['Fixtures/file3.txt', false, false],
            ['/Fixtures/file3.txt', false, false]
        ];
    }

    public function testPathOverride(): void
    {
        $file = basename(__FILE__);

        $this->assertFalse($this->locator->get('Dir/'.$file));

        $this->locator->add('Dir', __DIR__);

        $this->assertSame(__FILE__, $this->locator->get('Dir/'.$file));
    }
}
