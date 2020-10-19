<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\JsonFilter;

class JsonTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new JsonFilter;

        $values = [
            '"23"'              => "23",
            '{"foo": "bar"}'    => ["foo" => "bar"],
            '{"foo": "23"}'     => ["foo" => "23"],
            '"äöü"'   => "äöü" // unicode support please
        ];
        foreach ($values as $in => $out) {
            $this->assertSame($filter->filter($in), $out);
        }

    }

}
