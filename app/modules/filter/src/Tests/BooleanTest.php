<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\BooleanFilter;

class BooleanTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new BooleanFilter;

        $values = [
            0   => false,
            ""  => false,
            "1" => true

        ];
        foreach ($values as $in => $out) {
            $this->assertSame($filter->filter($in), $out);
        }

    }

}
