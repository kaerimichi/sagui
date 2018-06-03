<?php
declare(strict_types=1);

namespace Infrastructure\App;

use Atlas\Orm\Atlas;
use DI\ContainerBuilder;
use Infrastructure\Controller\FrontendController;
use Infrastructure\Plugin\Plugin;
use Infrastructure\Service\Collector\MiddlewareCollector;
use Infrastructure\Service\Collector\PluginCollector;
use Infrastructure\Service\Collector\RouteCollector;

class Sagui extends \Slim\App
{
    /**
     * Sagui constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $containerBuilder = new ContainerBuilder;
        if (getenv('APP_ENV') === 'prod') {
            $containerBuilder->enableDefinitionCache();
            $containerBuilder->enableCompilation(\dirname(__DIR__, 3).'/var/cache');
        }

        $containerBuilder = $this->defineDefaultDependencies($containerBuilder);
        $containerBuilder = $this->definePluginsDependencies($containerBuilder);
        $container = $containerBuilder->build();

        parent::__construct($container);
    }

    public function bootstrap(): void
    {
        $this->loadPluginConfig();
        $this->defineRoutes($this->getContainer()->get(RouteCollector::class));
        $this->defineMiddlewares($this->getContainer()->get(MiddlewareCollector::class));
    }

    public function loadPluginConfig(): void
    {
        $this->getContainer()->get(PluginCollector::class)
            ->bootPlugins($this->getContainer()->get(Atlas::class));
    }

    /**
     * @param ContainerBuilder $builder
     * @return ContainerBuilder
     * @throws \Infrastructure\Exception\FileNotFoundException
     * @throws \ReflectionException
     */
    protected function definePluginsDependencies(ContainerBuilder $builder): ContainerBuilder
    {
        $pluginCollector = new PluginCollector();
        $pluginCollector->addDefinition(include \dirname(__DIR__, 2).'/App/config/plugins.php');

        $builder->addDefinitions([PluginCollector::class => function () use ($pluginCollector) {
            return $pluginCollector;
        }]);

        /** @var Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $builder->addDefinitions($plugin->getDependencies());
        }

        return $builder;
    }

    /**
     * @param ContainerBuilder $builder
     * @return ContainerBuilder
     */
    protected function defineDefaultDependencies(ContainerBuilder $builder): ContainerBuilder
    {
        $definitions = array_merge(
            require \dirname(__DIR__, 3).'/config/default.'.getenv('APP_ENV').'.php',
            require 'default_config.php'
        );

        $builder->addDefinitions(__DIR__ . '/slim_dependencies.php');
        $builder->addDefinitions($definitions);
        $builder->addDefinitions(__DIR__ . '/base_dependencies.php');

        return $builder;
    }

    /**
     * @param RouteCollector $routeCollector
     */
    protected function defineRoutes(RouteCollector $routeCollector): void
    {
        foreach ($routeCollector as $pathConf => $actionConf) {
            [$verb, $path] = explode('@', $pathConf);
            [$controller, $method] = explode('::', $actionConf);

            $this->map([$verb], $path, [$controller, $method]);
        }

        $this->get('/', [FrontendController::class, 'home']);
    }

    /**
     * @param MiddlewareCollector $middlewareCollector
     */
    protected function defineMiddlewares(MiddlewareCollector $middlewareCollector): void
    {
        foreach ($middlewareCollector as $middleware) {
            $this->add($middleware);
        }
    }
}
