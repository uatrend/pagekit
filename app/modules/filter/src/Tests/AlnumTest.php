<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\AlnumFilter;

class AlnumTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new AlnumFilter;

        $values = [
            /* here are the ones the filter should not change */
            "abc"   => "abc",
            "123"   => "123",
            "äöü"   => "äöü", // unicode support please
            /* now the ones the filter has to fix */
            "?"     => "",
            "abc!"  => "abc",
            "     " => "",
            "!§$%&/()="   => "",
            "abc123!?) abc" => "abc123abc"
        ];
        foreach ($values as $in => $out) {
            $this->assertEquals($filter->filter($in), $out);
        }

    }

}
