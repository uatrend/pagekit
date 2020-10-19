<?php

namespace Pagekit\Database;

use Doctrine\DBAL\Statement;
use Doctrine\DBAL\Driver\ResultStatement;
use Pagekit\Database\Query\QueryBuilder;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection as BaseConnection;
use Doctrine\DBAL\Driver;
use Pagekit\Database\Utility;

class Connection extends BaseConnection
{
    const SINGLE_QUOTED_TEXT = '\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'';
    const DOUBLE_QUOTED_TEXT = '"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"';

    /**
     * The database utility.
     */
    protected ?Utility $utility = null;

    /**
     * The table prefix.
     */
    protected ?string $prefix = null;

    /**
     * The table prefix placeholder.
     */
    protected string $placeholder = '@';

    /**
     * The regex for parsing SQL query parts.
     */
    protected array $regex;

    /**
     * Initializes a new instance of the Connection class.
     *
     * @param array         $params
     * @param Driver        $driver
     * @param Configuration $config
     * @param EventManager  $eventManager
     */
    public function __construct(array $params, Driver $driver, Configuration $config = null, EventManager $eventManager = null)
    {
        if (!isset($params['defaultTableOptions'])) {
            $params['defaultTableOptions'] = [];
        }

        foreach (['engine', 'charset', 'collate'] as $name) {
            if (isset($params[$name])) {
                $params['defaultTableOptions'][$name] = $params[$name];
                unset($params[$name]);
            }
        }

        if (isset($params['prefix'])) {
            $this->prefix = $params['prefix'];
        }

        $this->regex = [
            'quotes' => "/([^'\"]+)(?:".self::DOUBLE_QUOTED_TEXT."|".self::SINGLE_QUOTED_TEXT.")?/As",
            'placeholder' => "/".preg_quote($this->placeholder, '/')."([a-zA-Z_][a-zA-Z0-9_]*)/"
        ];

        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * Gets the database utility.
     */
    public function getUtility(): Utility
    {
        if (!$this->utility) {
            $this->utility = new Utility($this);
        }

        return $this->utility;
    }

    /**
     * Gets the table prefix.
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Replaces the table prefix placeholder with actual one.
     *
     * @param  string $query
     */
    public function replacePrefix($query): string
    {
        $offset = 0;
        $length = strlen($this->prefix) - strlen($this->placeholder);

        foreach ($this->getUnquotedQueryParts($query) as $part) {

            if (strpos($part[0], $this->placeholder) === false) {
                continue;
            }

            $replace = preg_replace($this->regex['placeholder'], $this->prefix.'$1', $part[0], -1, $count);

            if ($count) {
                $query = substr_replace($query, $replace, $part[1] + $offset, strlen($part[0]));
                $offset += $length;
            }
        }

        return $query;
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result as an object.
     *
     * @param  string $statement
     * @param  array  $params
     * @param  string $class
     * @param  array  $args
     * @return mixed
     */
    public function fetchObject($statement, array $params = [], $class = 'stdClass', $args = [])
    {
        return $this->executeQuery($statement, $params)->fetchObject($class, $args);
    }

    /**
     * Prepares and executes an SQL query and returns the result as an array of objects.
     *
     * @param  string $statement
     * @param  array  $params
     * @param  string $class
     * @param  array  $args
     */
    public function fetchAllObjects($statement, array $params = [], $class = 'stdClass', $args = []): array
    {
        return $this->executeQuery($statement, $params)->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class, $args);
    }

    /**
     * @{inheritdoc}
     */
    public function prepare($statement): Statement
    {
        return parent::prepare($this->replacePrefix($statement));
    }

    /**
     * @{inheritdoc}
     */
    public function exec($statement): int
    {
        return parent::exec($this->replacePrefix($statement));
    }

    /**
     * @{inheritdoc}
     */
    public function executeQuery($query, array $params = [], $types = [], QueryCacheProfile $qcp = null): ResultStatement
    {
        return parent::executeQuery($this->replacePrefix($query), $params, $types, $qcp);
    }

    /**
     * @{inheritdoc}
     */
    public function executeUpdate($query, array $params = [], array $types = []): int
    {
        return parent::executeUpdate($this->replacePrefix($query), $params, $types);
    }

    /**
     * @{inheritdoc}
     */
    public function createQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this);
    }

    /**
     * Parses the unquoted SQL query parts.
     *
     * @param  string $query
     */
    protected function getUnquotedQueryParts($query): array
    {
        preg_match_all($this->regex['quotes'], $query, $parts, PREG_OFFSET_CAPTURE);

        return $parts[1];
    }
}
