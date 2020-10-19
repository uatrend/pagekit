<?php

namespace Pagekit\Console\NodeVisitor;

use PhpParser\Lexer;
use PhpParser\Node;
use Symfony\Component\Templating\EngineInterface;

abstract class NodeVisitor
{
    public ?string $file = null;

    public array $results = [];

    public EngineInterface $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function getEngine(): EngineInterface
    {
        return $this->engine;
    }

    /**
     * Starts traversing an array of files.
     *
     * @param  array $files
     */
    abstract public function traverse(array $files): array;

    /**
     * @param  string $name
     */
    protected function loadTemplate($name): string
    {
        return $this->file = $name;
    }
}