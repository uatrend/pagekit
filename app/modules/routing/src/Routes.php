<?php

namespace Pagekit\Routing;

class Routes implements \IteratorAggregate, ResourceInterface
{
    protected array $routes = [];

    /**
     * @var callable[]
     */
    protected array $callbacks = [];

    /**
     * @var array[]
     */
    protected array $aliases = [];

    protected string $prefix = '@';

    /**
     * @var int
     */
    protected $modified = 0;

    /**
     * Adds a route.
     *
     * @param  mixed $route
     */
    public function add($route): Route
    {
        if (is_array($route)) {
            $route = $this->createRoute($route);
        }

        return $this->routes[] = $route;
    }

    /**
     * Maps a route to a callback (GET).
     *
     * @param  string   $path
     * @param  callable $callback
     */
    public function get($path, $callback): Route
    {
        return $this->match($path, $callback)->setMethods('GET');
    }

    /**
     * Maps a route to a callback (POST).
     *
     * @param  string   $path
     * @param  callable $callback
     */
    public function post($path, $callback): Route
    {
        return $this->match($path, $callback)->setMethods('POST');
    }

    /**
     * Maps a route to a callback.
     *
     * @param  string   $path
     * @param  callable $callback
     */
    public function match($path, $callback): Route
    {
        return $this->add(['path' => $path, 'controller' => $callback]);
    }

    /**
     * Gets a registered callback.
     *
     * @param  string $name
     * @return callable|null
     */
    public function getCallback($name): ?callable
    {
        return isset($this->callbacks[$name]) ? $this->callbacks[$name] : null;
    }

    /**
     * Adds an alias.
     *
     * @param  string $path
     * @param  string $name
     * @param  array  $defaults
     */
    public function alias($path, $name, array $defaults = []): Route
    {
        $path = preg_replace('/^[^\/]/', '/$0', $path);

        return $this->aliases[$name] = $this->createRoute(compact('name', 'path', 'defaults'));
    }

    /**
     * Adds a redirect route.
     *
     * @param  string $path
     * @param  string $redirect
     * @param  array  $defaults
     */
    public function redirect($path, $redirect, array $defaults = []): Route
    {
        $defaults['_redirect'] = $redirect;

        return $this->add(compact('path', 'defaults'));
    }

    /**
     * Gets aliases.
     *
     * @return array[]
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * {@inheritdoc}
     */
    public function getModified(): int
    {
        return $this->modified;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->routes, $this->aliases]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        list($this->routes, $this->aliases) = unserialize($serialized);
    }

    /**
     * Creates a route from array definition.
     *
     * @param  array $config
     */
    protected function createRoute(array $config): Route
    {
        $name = isset($config['name']) ? $config['name'] : $this->generateRouteName($config);
        $defaults = isset($config['defaults']) ? $config['defaults'] : [];
        $requirements = isset($config['requirements']) ? $config['requirements'] : [];
        $options = isset($config['options']) ? $config['options'] : [];
        $host = isset($config['host']) ? $config['host'] : '';
        $schemes = isset($config['schemes']) ? $config['schemes'] : [];
        $methods = isset($config['methods']) ? $config['methods'] : [];
        $condition = isset($config['condition']) ? $config['condition'] : '';

        $options['controller'] = isset($config['controller']) ? $config['controller'] : '';

        if (!is_string($options['controller']) && is_callable($options['controller'])) {
            $this->callbacks[$name] = $options['controller'];
            unset($options['controller']);
        } elseif ($options['controller']) {
            foreach((array) $options['controller'] as $controller) {

                if (is_callable($controller)) {
                    $refl = new \ReflectionMethod($controller);
                    $defaults['_controller'] = $controller;
                } else {
                    $refl = new \ReflectionClass($controller);
                }

                $this->modified = max($this->modified, filemtime($refl->getFileName()));
            }
        }

        return (new Route($config['path'], $defaults, $requirements, $options, $host, $schemes, $methods, $condition))->setName($name);
    }

    /**
     * Creates a route name from the routes path.
     *
     * @param  array $config
     */
    protected function generateRouteName(array $config): string
    {
        $name = ltrim($config['path'], '/');
        $name = trim(str_replace(array(':', '|', '-'), '_', $name), '_');
        $name = preg_replace('/[^a-z0-9A-Z_.\/]+/', '', $name);
        return $this->prefix.$name;
    }
}
