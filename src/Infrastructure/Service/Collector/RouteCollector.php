<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

use Infrastructure\Exception\FileNotFoundException;

class RouteCollector extends Collector
{
    /**
     * @param string $definitionPath
     * @throws FileNotFoundException
     */
    public function addDefinition(string $definitionPath): void
    {
        if (!is_file($definitionPath)) {
            throw new FileNotFoundException('The path is wrong or the file don\'t exist.');
        }

        /** @var array $appRoutes */
        $appRoutes = include $definitionPath;

        $this->bag = array_merge($this->bag, $appRoutes);
    }
}