<?php

namespace Pagekit\Config;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Pagekit\Database\Connection;

class ConfigManager implements \IteratorAggregate
{
    protected \Pagekit\Database\Connection $connection;

    protected string $table;

    protected ?array $cache = null;

    protected array $configs = [];

    /**
     * Constructor.
     *
     * @param Connection $connection
     * @param array      $config
     */
    public function __construct(Connection $connection, array $config)
    {
        $this->connection = $connection;
        $this->table      = $config['table'];
    }

    /**
     * Get shortcut.
     *
     * @see get()
     */
    public function __invoke($name)
    {
        return $this->get($name);
    }

    /**
     * Checks if a config exists.
     *
     * @param  string $name
     */
    public function has($name): bool
    {
        return isset($this->configs[$name]) || $this->fetch($name);
    }

    /**
     * Gets a config, creates a new config if none existent.
     *
     * @param  string $name
     * @return Config
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            $this->set($name, new Config());
        }

        if (isset($this->configs[$name])) {
            return $this->configs[$name];
        }
    }

    /**
     * Sets a config.
     *
     * @param string $name
     * @param mixed  $config
     */
    public function set($name, $config): void
    {
        if (is_array($config)) {
            $config = (new Config())->merge($config);
        }

        $this->configs[$name] = $config;

        if ($config->dirty()) {

            $data = ['name' => $name, 'value' => json_encode($config, JSON_UNESCAPED_UNICODE)];

            if ($this->connection->getDatabasePlatform() instanceof MySqlPlatform) {
                $this->connection->executeQuery("INSERT INTO {$this->table} (name, value) VALUES (:name, :value) ON DUPLICATE KEY UPDATE value = :value", $data);
            } elseif (!$this->connection->update($this->table, $data, compact('name'))) {
                $this->connection->insert($this->table, $data);
            }
        }
    }

    /**
     * Removes a config.
     *
     * @param string $name
     */
    public function remove($name): void
    {
        $this->connection->delete($this->table, compact('name'));
    }

    /**
     * Returns an iterator.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->configs);
    }

    /**
     * Fetches config from database.
     *
     * @param  string $name
     * @return null|Config
     */
    protected function fetch($name)
    {
        if ($this->cache === null) {
            $this->cache = $this->connection->executeQuery("SELECT name, value FROM {$this->table}")->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
        }

        if (isset($this->cache[$name]) && $values = @json_decode($this->cache[$name], true)) {
            return $this->configs[$name] = new Config($values);
        }
    }
}
