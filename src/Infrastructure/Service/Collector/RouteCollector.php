<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

class RouteCollector extends Collector
{
    /**
     * @param array $definitions
     */
    public function addDefinition(array $definitions): void
    {
        $this->bag = array_merge($this->bag, $definitions);
    }
}