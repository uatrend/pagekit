<?php

namespace Pagekit\Debug\DataCollector;

use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\Storage\StorageInterface;

class ProfileDataCollector implements DataCollectorInterface
{
    protected \DebugBar\Storage\StorageInterface $storage;

    /**
     * Constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(): array
    {
        return ['requests' => $this->storage->find()];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'profile';
    }
}
