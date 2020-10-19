<?php

namespace Pagekit\Twig;

use Twig\Environment;
use Twig\TemplateWrapper;
use Twig\Error\LoaderError;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\StreamingEngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TwigEngine implements EngineInterface, StreamingEngineInterface
{
    protected \Twig\Environment $environment;
    protected \Symfony\Component\Templating\TemplateNameParserInterface $parser;

    /**
     * Constructor.
     *
     * @param Environment           $environment
     * @param TemplateNameParserInterface $parser
     */
    public function __construct(Environment $environment, TemplateNameParserInterface $parser)
    {
        $this->environment = $environment;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = []): string
    {
        return $this->load($name)->render($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function stream($name, array $parameters = []): void
    {
        $this->load($name)->display($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name): bool
    {
        try {
            $this->environment->getLoader()->getSource((string) $name);
        } catch (LoaderError $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($name): bool
    {
        $template = $this->parser->parse($name);

        return 'twig' === $template->get('engine');
    }

    /**
     * Loads the given template.
     *
     * @param  string|TemplateWrapper $name
     * @return TemplateWrapper
     * @throws \InvalidArgumentException
     */
    protected function load($name): TemplateWrapper
    {
        try {
            return $this->environment->load((string) $name);
        } catch (LoaderError $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
