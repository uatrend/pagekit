<?php

namespace Pagekit\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Tests\FileUtil;
use Pagekit\Filesystem\Adapter\FileAdapter;
use Pagekit\Filesystem\Filesystem;

class FileTest extends TestCase
{
    use FileUtil;

    protected ?Filesystem $file = null;
    protected ?string $fixtures = null;
    protected $workspace;

    public function setUp(): void
    {
        $this->file      = new Filesystem;
        $this->fixtures  = __DIR__.'/Fixtures';
        $this->workspace = $this->getTempDir('filesystem_');

        $this->file->registerAdapter('file', new FileAdapter(__DIR__, 'http://localhost'));
    }

    public function tearDown(): void
    {
        $this->removeDir($this->workspace);
    }

    public function testGetUrlLocal(): void
    {
        $this->assertSame('/Fixtures', $this->file->getUrl($this->fixtures));
        $this->assertSame('//localhost/Fixtures', $this->file->getUrl($this->fixtures, 3));
        $this->assertSame('http://localhost/Fixtures', $this->file->getUrl($this->fixtures, true));
    }

    public function testGetUrlExternal(): void
    {
        $ftp  = 'ftp://example.com';
        $http = 'http://username:password@example.com/path?arg=value#anchor';

        $this->assertSame('/', $this->file->getUrl($ftp));
        $this->assertSame('//example.com', $this->file->getUrl($ftp, 3));
        $this->assertSame($ftp, $this->file->getUrl($ftp, true));

        $this->assertSame('/path?arg=value#anchor', $this->file->getUrl($http));
        $this->assertSame('//username:password@example.com/path?arg=value#anchor', $this->file->getUrl($http, 3));
        $this->assertSame($http, $this->file->getUrl($http, true));
    }

    public function testGetUrlNotFound(): void
    {
        $dir = __DIR__.'/Directory';

        $this->assertFalse($this->file->getUrl($dir));
    }

    public function testGetPath(): void
    {
        $this->assertSame('/file1.txt', $this->file->getPath('/file1.txt'));
    }

    public function testExists(): void
    {
        $file1 = $this->fixtures.'/file1.txt';
        $file2 = $this->fixtures.'/file2.txt';
        $file3 = $this->fixtures.'/file3.txt';

        $this->assertTrue($this->file->exists($file1));
        $this->assertTrue($this->file->exists($file2));
        $this->assertTrue($this->file->exists([$file1, $file2]));
        $this->assertFalse($this->file->exists($file3));
        $this->assertFalse($this->file->exists([$file1, $file2, $file3]));
    }

    public function testCopyFile(): void
    {
        $file1 = $this->fixtures.'/file1.txt';

        $this->assertTrue($this->file->copy($file1, $this->workspace.'/file1.txt'));
        $this->assertTrue($this->file->exists($this->workspace.'/file1.txt'));
    }

    public function testCopyFileNotFound(): void
    {
        $file3 = $this->fixtures.'/file3.txt';

        $this->assertFalse($this->file->exists($file3));
        $this->assertFalse($this->file->copy($file3, $this->workspace.'/file3.txt'));
    }

    public function testCopyDir(): void
    {
        $this->assertTrue($this->file->copyDir($this->fixtures, $this->workspace));
        $this->assertTrue($this->file->exists($this->workspace.'/file1.txt'));
        $this->assertTrue($this->file->exists($this->workspace.'/file2.txt'));
    }

    public function testCopyDirNotFound(): void
    {
        $dir = __DIR__.'/Directory';

        $this->assertFalse($this->file->exists($dir));
        $this->assertFalse($this->file->copyDir($dir, $this->workspace));
    }

    public function testDeleteFile(): void
    {
        $file1 = $this->fixtures.'/file1.txt';

        $this->assertTrue($this->file->copy($file1, $this->workspace.'/file1.txt'));
        $this->assertTrue($this->file->delete($this->workspace.'/file1.txt'));
        $this->assertFalse($this->file->exists($this->workspace.'/file1.txt'));
    }

    public function testDeleteFileNotFound(): void
    {
        $file3 = $this->workspace.'/file3.txt';

        $this->assertFalse($this->file->exists($file3));
        $this->assertFalse($this->file->delete($file3));
    }

    public function testDeleteDir(): void
    {
        $dir = $this->workspace.'/Directory';

        $this->assertTrue($this->file->copyDir($this->fixtures, $dir));
        $this->assertTrue($this->file->delete($dir));
        $this->assertFalse($this->file->exists($dir));
    }

    public function testListDir(): void
    {
        $this->assertContains('file1.txt', $this->file->listDir($this->fixtures));
        $this->assertContains('file2.txt', $this->file->listDir($this->fixtures));
    }
}
