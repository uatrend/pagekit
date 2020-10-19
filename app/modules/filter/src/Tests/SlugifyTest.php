<?php

namespace Pagekit\Filter\Tests;

use PHPUnit\Framework\TestCase;
use Pagekit\Filter\SlugifyFilter;

class SlugifyTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new SlugifyFilter;

        $values = [
            'PAGEKIT'                  => 'pagekit',
            ":#*\"@+=;!><&.%()/'\\|[]" => "",
            "  a b ! c   "             => "a-b-c",
        ];

        foreach ($values as $in => $out) {
            $this->assertEquals($out, $filter->filter($in));
        }

    }
}
