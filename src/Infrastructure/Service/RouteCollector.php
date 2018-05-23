<?php
declare(strict_types=1);

namespace Infrastructure\Service;

use Infrastructure\Exception\FileNotFoundException;

class RouteCollector implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $routesDefinitions;

    /**
     * @var array
     */
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * @param string $definitionPath
     * @param string|null $key
     * @throws FileNotFoundException
     */
    public function addRoutesDefinition(string $definitionPath, string $key = null): void
    {
        if (!is_file($definitionPath)) {
            throw new FileNotFoundException('The path is wrong or the file don\'t exist.');
        }

        $key = $key ?? uniqid('route_', false);

        /** @var array $appRoutes */
        $appRoutes = include $definitionPath;
        $this->routesDefinitions[$key] = $appRoutes;

        $this->routes = array_merge($this->routes, $this->routesDefinitions[$key]);
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return (function () {
            foreach ($this->routes as $key => $val) {
                yield $key => $val;
            }
        })();
    }
}