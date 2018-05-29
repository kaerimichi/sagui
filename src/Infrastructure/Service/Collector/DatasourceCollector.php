<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

class DatasourceCollector extends Collector
{
    /**
     * @param array $definitionPath
     */
    public function addDefinition(array $definitionPath): void
    {
        $this->bag = array_merge($this->bag, $definitionPath);
    }

    /**
     * @return array
     */
    public function getDataSources(): ?array
    {
        return $this->bag;
    }
}