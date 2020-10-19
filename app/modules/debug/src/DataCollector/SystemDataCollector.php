<?php

namespace Pagekit\Debug\DataCollector;

use DebugBar\DataCollector\DataCollectorInterface;
use Pagekit\Info\InfoHelper;

class SystemDataCollector implements DataCollectorInterface
{
    protected \Pagekit\Info\InfoHelper $info;

    /**
     * Constructor.
     *
     * @param InfoHelper $info
     */
    function __construct(InfoHelper $info)
    {
        $this->info = $info;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(): array
    {
        return $this->info->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'system';
    }
}
