<?php
declare(strict_types=1);

namespace Infrastructure\App;

use DI\ContainerBuilder;
use Infrastructure\Controller\FrontendController;
use Infrastructure\Plugin\Plugin;
use Infrastructure\Service\PluginCollector;
use Infrastructure\Service\RouteCollector;

class Sagui extends \Slim\App
{
    /**
     * @var RouteCollector
     */
    private $routeCollector;

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
        $this->routeCollector = $this->getContainer()->get(RouteCollector::class);
        $this->defineRoutes();
    }

    /**
     * @param ContainerBuilder $builder
     * @return ContainerBuilder
     * @throws \ReflectionException
     */
    protected function definePluginsDependencies(ContainerBuilder $builder): ContainerBuilder
    {
        $pluginCollector = new PluginCollector();
        $pluginCollector->load(\dirname(__DIR__, 2).'/App/config/plugins.php');

        /** @var Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $builder->addDefinitions($plugin->getConfigPath().'/dependencies.php');
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
