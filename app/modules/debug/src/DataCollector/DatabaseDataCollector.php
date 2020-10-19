<?php

namespace Pagekit\Debug\DataCollector;

use DebugBar\Bridge\DoctrineCollector;
use Doctrine\DBAL\Logging\DebugStack;
use Pagekit\Database\Connection;

class DatabaseDataCollector extends DoctrineCollector
{
    protected \Pagekit\Database\Connection $connection;

    /**
     * Constructor.
     *
     * @param Connection $connection
     * @param DebugStack $debugStack
     */
    public function __construct(Connection $connection, DebugStack $debugStack = null)
    {
        $this->connection = $connection;
        $this->debugStack = $debugStack;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(): array
    {
        $driver = $this->connection->getDriver()->getName();

        return array_replace(compact('driver'), parent::collect());
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'database';
    }
}
