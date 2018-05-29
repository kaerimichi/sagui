<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

class MiddlewareCollector extends Collector
{
    /**
     * @param array $definitions
     */
    public function addDefinition(array $definitions): void
    {
        foreach ($definitions as $middleware => $conf) {
            if (isset($conf['priority'])) {
                $this->bag[$conf['priority']] = $middleware;
            } else {
                $this->bag[] = $middleware;
            }
        }
    }
}