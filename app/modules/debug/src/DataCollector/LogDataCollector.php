<?php

namespace Pagekit\Debug\DataCollector;

use DebugBar\DataCollector\DataCollectorInterface;
use Monolog\Handler\AbstractHandler;

class LogDataCollector extends AbstractHandler implements DataCollectorInterface
{
    protected array $messages = [];

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

        $this->messages[] = array_intersect_key($record, array_flip($keys));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(): array
    {
        return ['messages' => $this->messages];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'log';
    }
}
