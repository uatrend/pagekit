<?php

namespace Pagekit\Filter\Tests;

use Pagekit\Filter\PregReplaceFilter;

class PregReplaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PregReplace
     **/
    protected $filter;

    public function setUp(): void
    {
        $this->filter = new PregReplaceFilter;
    }

    public function testRuntimeException()
    {
        $this->expectException(\RuntimeException::class);

        $this->filter->filter('foo');
    }

    public function testModifierE()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filter->setPattern('/foo/e');
    }

    public function testPatternArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filter->setPattern(null);
    }

    public function testReplacementArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filter->setReplacement(null);
    }

    /**
     * @dataProvider provider
     */
    public function testFilter($pattern, $replacement, $in, $out)
    {
        $this->filter->setPattern($pattern);
        $this->assertSame($this->filter->getPattern(), $pattern);

        $this->filter->setReplacement($replacement);
        $this->assertSame($this->filter->getReplacement(), $replacement);

        $this->assertSame($this->filter->filter($in), $out);
    }

    public function provider()
    {
        return [
            ['/foo/i', '', 'Foobar', 'bar'],
            [['/foo/', '/bar/'], ['FOO', 'BAR'], 'foobar', 'FOOBAR']
        ];
    }

}
