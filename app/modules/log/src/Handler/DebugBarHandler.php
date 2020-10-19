<?php

namespace Pagekit\Log\Handler;

use DebugBar\DataCollector\DataCollectorInterface;
use Monolog\Handler\AbstractHandler;

class DebugBarHandler extends AbstractHandler implements DataCollectorInterface
{
    protected array $records = [];

    /**
     * {@inheritdoc}
     */
    public function handle(array $record): bool
    {
        if ($record['level'] < $this->level) {
            return false;
        }

        $keys = [
            'message',
            'level',
            'level_name',
            'channel'
        ];

        $this->records[] = array_intersect_key($record, array_flip($keys));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(): array
    {
        return ['records' => $this->records];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'log';
    }
}
