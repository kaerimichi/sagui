<?php
declare(strict_types=1);

namespace Infrastructure\App;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Infrastructure\Controller\FrontendController;
use Infrastructure\Plugin\Plugin;
use PhpParser\Node\Expr\AssignOp\Plus;

class Sagui extends App
{
    /**
     * @var string
     */
    private $definitionFile;

    /**
     * @var RouteCollector
     */
    private $routeCollector;

    /**
     * Sagui constructor.
     */
    public function __construct()
    {
        $this->definitionFile = \dirname(__DIR__, 3).'/config/default.'.getenv('APP_ENV').'.php';

        parent::__construct();

        $this->routeCollector = $this->getContainer()->get(RouteCollector::class);
    }

    public function bootstrap(): void
    {
        $this->defineRoutes();
    }

    /**
     * @param ContainerBuilder $builder
     */
    protected function configureContainer(ContainerBuilder $builder): void
    {
        if (getenv('APP_ENV') === 'prod') {
            $builder->enableDefinitionCache();
            $builder->enableCompilation(\dirname(__DIR__, 3).'/var/cache');
        }

        $builder->addDefinitions($this->definitionFile);
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
