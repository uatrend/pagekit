<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\StripNewlinesFilter;

class StripNewlinesTest extends TestCase
{
    /**
     * @dataProvider provideNewLineStrings
     */
    public function testFilter($input, $output): void
    {
        $filter = new StripNewlinesFilter;

        $this->assertEquals($output, $filter->filter($input));
    }

    public function provideNewLineStrings(): array
    {
        return [
            ['', ''],
            ["\n", ''],
            ["\r", ''],
            ["\r\n", ''],
            ['\n', '\n'],
            ['\r', '\r'],
            ['\r\n', '\r\n'],
            ["These newlines should\nbe removed by\r\nthe filter", 'These newlines shouldbe removed bythe filter']
        ];
    }
}
