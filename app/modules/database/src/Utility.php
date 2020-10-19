<?php

namespace Pagekit\Database;

use Pagekit\Database\Table;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Constraint;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Schema;

class Utility
{
    protected \Pagekit\Database\Connection $connection;

    protected AbstractSchemaManager $manager;

    protected Schema $schema;

    /**
     * Constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->manager = $this->connection->getSchemaManager();
        $this->schema = $this->manager->createSchema();
    }

    /**
     * Return the DBAL schema manager.
     */
    public function getSchemaManager(): AbstractSchemaManager
    {
        return $this->manager;
    }

    /**
     * Returns true if the given table exists.
     *
     * @param  string $table
     */
    public function tableExists($table): bool
    {
        return $this->tablesExist($table);
    }

    /**
     * Returns an existing database table.
     *
     * @param string $table
     *
     *
     * @throws SchemaException
     */
    public function getTable($table): Table
    {
        return new Table($this->schema->getTable($this->replacePrefix($table)), $this->connection);
    }

    /**
     * Returns true if all the given tables exist.
     *
     * @param  array $tables
     */
    public function tablesExist($tables): bool
    {
        $tables = array_map(fn($query) => $this->replacePrefix($query), (array) $tables);

        return $this->manager->tablesExist($tables);
    }

    /**
     * Creates a new database table.
     *
     * @param string $table
     * @param \Closure $callback
     */
    public function createTable($table, \Closure $callback): void
    {
        $table = $this->schema->createTable($this->replacePrefix($table));

        $callback(new Table($table, $this->connection));

        $this->manager->createTable($table);
    }

    /**
     * {@see AbstractSchemaManager::createConstraint}
     */
    public function createConstraint(Constraint $constraint, $table): void
    {
        $this->manager->createConstraint($constraint, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::createIndex}
     */
    public function createIndex(Index $index, $table): void
    {
        $this->manager->createIndex($index, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::createForeignKey}
     */
    public function createForeignKey(ForeignKeyConstraint $foreignKey, $table): void
    {
        $this->manager->createForeignKey($foreignKey, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropAndCreateConstraint}
     */
    public function dropAndCreateConstraint(Constraint $constraint, $table): void
    {
        $this->manager->dropAndCreateConstraint($constraint, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropAndCreateIndex}
     */
    public function dropAndCreateIndex(Index $index, $table): void
    {
        $this->manager->dropAndCreateIndex($index, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropAndCreateForeignKey}
     */
    public function dropAndCreateForeignKey(ForeignKeyConstraint $foreignKey, $table): void
    {
        $this->manager->dropAndCreateForeignKey($foreignKey, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropAndCreateTable}
     */
    public function dropAndCreateTable($table): void
    {
        $this->manager->dropAndCreateTable($table);
    }

    /**
     * {@see AbstractSchemaManager::renameTable}
     */
    public function renameTable($name, $newName): void
    {
        $this->manager->renameTable($this->replacePrefix($name), $this->replacePrefix($newName));
    }

    /**
     * @see AbstractSchemaManager::dropTable
     */
    public function dropTable($table): void
    {
        $this->manager->dropTable($this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropIndex}
     */
    public function dropIndex($index, $table): void
    {
        $this->manager->dropIndex($index, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropConstraint}
     */
    public function dropConstraint(Constraint $constraint, $table): void
    {
        $this->manager->dropConstraint($constraint, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::dropForeignKey}
     */
    public function dropForeignKey($foreignKey, $table): void
    {
        $this->manager->dropForeignKey($foreignKey, $this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::listTableColumns}
     */
    public function listTableColumns($table, $database = null): array
    {
        return $this->manager->listTableColumns($this->replacePrefix($table), $database);
    }

    /**
     * {@see AbstractSchemaManager::listTableIndexes}
     */
    public function listTableIndexes($table): array
    {
        return $this->manager->listTableIndexes($this->replacePrefix($table));
    }

    /**
     * {@see AbstractSchemaManager::listTableDetails}
     */
    public function listTableDetails($tableName): \Doctrine\DBAL\Schema\Table
    {
        return $this->manager->listTableDetails($this->replacePrefix($tableName));
    }

    /**
     * {@see AbstractSchemaManager::listTableForeignKeys}
     */
    public function listTableForeignKeys($table, $database = null): array
    {
        return $this->manager->listTableForeignKeys($this->replacePrefix($table), $database);
    }

    /**
     * Proxy method call to database schema manager.
     *
     * @param  string $method
     * @param  array $args
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!method_exists($this->manager, $method)) {
            throw new \BadMethodCallException(sprintf('Undefined method call "%s::%s"', get_class($this->manager), $method));
        }

        return call_user_func_array([$this->manager, $method], $args);
    }

    /**
     * Migrates the database.
     */
    public function migrate(): void {
        $diff = Comparator::compareSchemas($this->manager->createSchema(), $this->schema);

        foreach ($diff->toSaveSql($this->connection->getDatabasePlatform()) as $query) {
            $this->connection->executeQuery($query);
        }
    }

    /**
     * Replaces the table prefix placeholder with actual one.
     *
     * @param  string $query
     */
    protected function replacePrefix($query): string
    {
        return $this->connection->replacePrefix($query);
    }
}
