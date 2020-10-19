<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\PregReplaceFilter;

class PregReplaceTest extends TestCase
{
    protected ?PregReplaceFilter $filter = null;

    public function setUp(): void
    {
        $this->filter = new PregReplaceFilter;
    }

    public function testRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->filter->filter('foo');
    }

    public function testModifierE(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filter->setPattern('/foo/e');
    }

    public function testPatternArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filter->setPattern(null);
    }

    public function testReplacementArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filter->setReplacement(null);
    }

    /**
     * @dataProvider provider
     */
    public function testFilter($pattern, $replacement, $in, $out): void
    {
        $this->filter->setPattern($pattern);
        $this->assertSame($this->filter->getPattern(), $pattern);

        $this->filter->setReplacement($replacement);
        $this->assertSame($this->filter->getReplacement(), $replacement);

        $this->assertSame($this->filter->filter($in), $out);
    }

    public function provider(): array
    {
        return [
            ['/foo/i', '', 'Foobar', 'bar'],
            [['/foo/', '/bar/'], ['FOO', 'BAR'], 'foobar', 'FOOBAR']
        ];
    }

}
