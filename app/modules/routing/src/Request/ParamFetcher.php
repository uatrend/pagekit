<?php

namespace Pagekit\Routing\Request;

use Pagekit\Filter\FilterManager;
use Symfony\Component\HttpFoundation\Request;

class ParamFetcher implements ParamFetcherInterface
{
    protected ?Request $request = null;

    protected ?array $params = null;

    protected \Pagekit\Filter\FilterManager $filterManager;

    /**
     * Constructor.
     *
     * @param  FilterManager  $filterManager
     */
    public function __construct(FilterManager $filterManager = null)
    {
        $this->filterManager = $filterManager ?: new FilterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $params, array $options): void
    {
        $this->params = [];

        foreach ($params as $name => $type) {

            if (is_numeric($name)) {
                $name = $type;
                $type = '';
            }

            $this->params[] = ['name' => $name, 'type' => $type, 'options' => isset($options[$name]) ? $options[$name] : []];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterManager(FilterManager $filterManager): void
    {
        $this->filterManager = $filterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function get($index)
    {
        if (!isset($this->params[$index])) {
            return null;
        }

        /**
         * @var string $name
         * @var string $type
         * @var array  $options
         */
        extract($this->params[$index]);

        foreach (['query', 'request'] as $bag) {

            $value = $this->request->$bag->get($name);

            if ($value !== null) {

                if ($type == 'array') {

                    $value = (array) $value;

                } elseif (strpos($type, '[]') !== false) {

                    $value = (array) $value;
                    $filter = $this->filterManager->get(str_replace('[]', '', $type), $options);

                    array_walk($value, function(&$val) use ($filter, $name) {

                        if (is_array($val)) {
                            throw new \RuntimeException(sprintf("Query parameter cannot be a nested array.", $name));
                        }

                        $val = $filter->filter($val);
                    });

                } elseif ($type) {

                    $value = $this->filterManager->get($type, $options)->filter($value);

                }

                break;
            }
        }

        return $value;
    }
}
