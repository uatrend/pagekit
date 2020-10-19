<?php

namespace Pagekit\Twig;

use Twig\Error\LoaderError;
use Pagekit\View\Loader\FilesystemLoader;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\TemplateNameParserInterface;

class TwigLoader extends \Twig\Loader\FilesystemLoader
{
    protected \Pagekit\View\Loader\FilesystemLoader $loader;
    protected \Symfony\Component\Templating\TemplateNameParserInterface $parser;

    /**
     * Constructor.
     *
     * @param FilesystemLoader            $loader
     * @param TemplateNameParserInterface $parser
     */
    public function __construct(FilesystemLoader $loader, TemplateNameParserInterface $parser = null)
    {
        parent::__construct([]);

        $this->loader = $loader;
        $this->parser = $parser ?: new TemplateNameParser();
    }

    /**
     * {@inheritdoc}
     */
    protected function findTemplate($template, $throw = true)
    {
        $key = (string) $template;

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $file = $this->loader->load($this->parser->parse($template));

        if (false === $file || null === $file) {
            throw new LoaderError(sprintf('Unable to find template "%s".', $key));
        }

        return $this->cache[$key] = $file;
    }
}
