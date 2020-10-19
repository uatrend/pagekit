<?php

namespace Pagekit\Routing;

use Symfony\Component\Routing\Route;
use Pagekit\Routing\ResourceInterface;
use Pagekit\Routing\Generator\UrlGenerator;
use Pagekit\Routing\Generator\UrlGeneratorDumper;
use Pagekit\Routing\Generator\UrlGeneratorInterface;
use Pagekit\Routing\Loader\LoaderInterface;
use Pagekit\Routing\RequestContext as Context;
use Pagekit\Routing\Matcher\Dumper\PhpMatcherDumper; // added to 1.0.18
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
// use Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper; // deprecated since 4.3
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class Router implements RouterInterface, UrlGeneratorInterface
{
    protected ResourceInterface $resource;

    protected LoaderInterface $loader;

    protected RequestStack $stack;

    /**
     * @var RequestContext
     */
    protected $context;

    protected ?UrlMatcher $matcher = null;

    protected ?UrlGenerator $generator = null;

    protected ?RouteCollection $routes = null;

    protected array $options;

    protected ?array $cache = null;

    /**
     * @var ParamsResolverInterface[]
     */
    protected array $resolver = [];

    /**
     * Constructor.
     *
     * @param ResourceInterface $resource
     * @param LoaderInterface   $loader
     * @param RequestStack      $stack
     * @param array             $options
     */
    public function __construct(ResourceInterface $resource, LoaderInterface $loader, RequestStack $stack, array $options = [])
    {
        $this->resource = $resource;
        $this->loader   = $loader;
        $this->stack    = $stack;
        $this->context  = new Context();
        $this->options  = array_replace([
            'cache'     => null,
            'matcher'   => 'Symfony\Component\Routing\Matcher\UrlMatcher',
            'generator' => 'Pagekit\Routing\Generator\UrlGenerator'
        ], $options);
    }

    /**
     * Get the current request.
     */
    public function getRequest(): ?Request
    {
        return $this->stack->getCurrentRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): RequestContext
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context): void
    {
        $this->context = $context;
    }

    /**
     * Gets the router's options.
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Sets router's the options.
     *
     * @param array $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
    }

    /**
     * Set an router's option.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setOption($name, $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * Gets a route.
     *
     * @param  string $name
     */
    public function getRoute($name): ?Route
    {
        return $this->getRouteCollection()->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(): RouteCollection
    {
        if (!$this->routes) {
            $this->routes = $this->loader->load($this->resource);
        }

        return $this->routes;
    }

    /**
     * Gets the URL matcher instance.
     */
    public function getMatcher(): UrlMatcher
    {
        if (!$this->matcher) {
            if ($cache = $this->getCache('%s/%s.matcher.cache')) {

                $class = sprintf('UrlMatcher%s', $cache['key']);

                if (!$cache['fresh']) {
                    $options = ['class' => $class, 'base_class' => $this->options['matcher']];
                    $this->writeCache($cache['file'], (new PhpMatcherDumper($this->getRouteCollection()))->dump($options));
                }

                require_once $cache['file'];

                $this->matcher = new $class($this->context);

            } else {

                $class = $this->options['matcher'];

                $this->matcher = new $class($this->getRouteCollection(), $this->context);
            }
        }

        return $this->matcher;
    }

    /**
     * Gets the UrlGenerator instance associated with this Router.
     */
    public function getGenerator(): UrlGenerator
    {
        if (!$this->generator) {
            if ($cache = $this->getCache('%s/%s.generator.cache')) {

                $class = sprintf('UrlGenerator%s', $cache['key']);

                if (!$cache['fresh']) {
                    $options = ['class' => $class, 'base_class' => $this->options['generator']];
                    $this->writeCache($cache['file'], (new UrlGeneratorDumper($this->getRouteCollection()))->dump($options));
                }

                require_once $cache['file'];

                $this->generator = new $class($this->context);

            } else {

                $class = $this->options['generator'];

                $this->generator = new $class($this->getRouteCollection(), $this->context);
            }
        }

        return $this->generator;
    }

    /**
     * Returns a redirect response.
     *
     * @param  string  $url
     * @param  array   $parameters
     * @param  int     $status
     * @param  array   $headers
     */
    public function redirect($url = '', $parameters = [], $status = 302, $headers = []): RedirectResponse
    {
        try {

            $url = $this->generate($url, $parameters);

        } catch (RouteNotFoundException $e) {

            if (filter_var($url, FILTER_VALIDATE_URL) === false && strpos($url, '/') !== 0) {
                $url = "{$this->getRequest()->getBaseUrl()}/$url";
            }
        }

        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo): array
    {
        $this->context->fromRequest($this->getRequest());

        $params = $this->getMatcher()->match($pathinfo);

        if ($resolver = $this->getResolver($params)) {
            $params = $resolver->match($params);
        }

        if (false !== $pos = strpos($params['_route'], '?')) {
            $params['_route'] = substr($params['_route'], 0, $pos);
        }

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        $generator = $this->getGenerator();

        if ($fragment = strstr($name, '#')) {
            $name = strstr($name, '#', true);
        }

        if ($query = substr(strstr($name, '?'), 1)) {
            parse_str($query, $params);
            $name       = strstr($name, '?', true);
            $parameters = array_replace($parameters, $params);
        }

        if ($referenceType !== self::LINK_URL
            && ($props = $generator->getRouteProperties($generator->generate($name, $parameters, 'link')) or $props = $generator->getRouteProperties($name))
            && $resolver = $this->getResolver($props[1])
        ) {
            $parameters = $resolver->generate($parameters);
        }

        return $generator->generate($name, $parameters, $referenceType).$fragment;
    }

    /**
     * Gets cache info.
     *
     * @param  string $file
     * @return array|null
     */
    protected function getCache($file): ?array
    {
        if (!$this->options['cache']) {
            return null;
        }

        if (!$this->cache) {
            $this->cache = ['key' => sha1(serialize($this->resource).serialize($this->options)), 'modified' => $this->resource->getModified()];
        }

        $file  = sprintf($file, $this->options['cache'], $this->cache['key']);
        $fresh = file_exists($file) && (!$this->cache['modified'] || filemtime($file) >= $this->cache['modified']);

        return array_merge(compact('fresh', 'file'), $this->cache);
    }

    /**
     * Writes cache file.
     *
     * @param  string $file
     * @param  string $content
     * @throws \RuntimeException
     */
    protected function writeCache($file, $content): void
    {
        if (!file_put_contents($file, $content)) {
            throw new \RuntimeException("Failed to write cache file ($file).");
        }
    }

    /**
     * Gets resolver instance from parameters.
     *
     * @param  array $parameters
     */
    protected function getResolver(array $parameters = []): ?ParamsResolverInterface
    {
        $resolver = isset($parameters['_resolver']) ? $parameters['_resolver'] : false;

        if (!isset($this->resolver[$resolver])) {

            if (!is_subclass_of($resolver, 'Pagekit\Routing\ParamsResolverInterface')) {
                return null;
            }

            $this->resolver[$resolver] = new $resolver;
        }

        return $this->resolver[$resolver];
    }
}