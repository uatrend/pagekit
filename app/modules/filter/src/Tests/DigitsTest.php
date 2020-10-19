<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\DigitsFilter;

class DigitsTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new DigitsFilter;

        $values = [
            /* here are the ones the filter should not change */
            "123"   => "123",
            /* now the ones the filter has to fix */
            "abc"   => "",
            "äöü"   => "", // unicode support please
            "?"     => "",
            "     " => "",
            "!§$%&/()="   => "",
            "abc123!?) abc" => "123"
        ];
        foreach ($values as $in => $out) {
            $this->assertEquals($filter->filter($in), $out);
        }

    }

}
