<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

use Infrastructure\Exception\FileNotFoundException;

class MiddlewareCollector extends Collector
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

        $middlewares = include $definitionPath;
        foreach ($middlewares as $middleware => $conf) {
            if (isset($conf['priority'])) {
                $this->bag[$conf['priority']] = $middleware;
            } else {
                $this->bag[] = $middleware;
            }
        }
    }
}