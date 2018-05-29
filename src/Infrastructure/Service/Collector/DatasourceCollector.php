<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

class DatasourceCollector extends Collector
{
    /**
     * @param string $definitionPath
     */
    public function addDefinition(string $definitionPath): void
    {
        if (!is_file($definitionPath)) {
            return;
        }

        $this->bag = array_merge($this->bag, include $definitionPath);
    }

    /**
     * @return array
     */
    public function getDataSources(): ?array
    {
        return $this->bag;
    }
}