<?php
declare(strict_types=1);

namespace Infrastructure\App;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Infrastructure\Controller\FrontendController;
use Infrastructure\Service\RouteCollector;

class Sagui extends App
{
    /**
     * @var RouteCollector
     */
    private $routeCollector;

    public function bootstrap(): void
    {
        $this->routeCollector = $this->getContainer()->get(RouteCollector::class);
        $this->defineRoutes();
    }

    /**
     * @param ContainerBuilder $builder
     */
    protected function configureContainer(ContainerBuilder $builder): void
    {
        $definitions = array_merge(
            require \dirname(__DIR__, 3).'/config/default.'.getenv('APP_ENV').'.php',
            require 'default_config.php'
        );

        if (getenv('APP_ENV') === 'prod') {
            $builder->enableDefinitionCache();
            $builder->enableCompilation(\dirname(__DIR__, 3).'/var/cache');
        }

        $builder->addDefinitions($definitions);
        $builder->addDefinitions(__DIR__ . '/base_dependencies.php');

        parent::configureContainer($builder);
    }

    protected function defineRoutes(): void
    {
        foreach ($this->routeCollector as $pathConf => $actionConf) {
            [$verb, $path] = explode('@', $pathConf);
            [$controller, $method] = explode('::', $actionConf);

            $this->map([$verb], $path, [$controller, $method]);
        }

        $this->get('/', [FrontendController::class, 'home']);
        $this->get('/{params:.*}', [FrontendController::class, 'handler']);
    }
}
