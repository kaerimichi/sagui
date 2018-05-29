<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Infrastructure\Service\Collector\RouteCollector;
use Infrastructure\Service\Collector\PluginCollector;
use Infrastructure\Service\Collector\DatasourceCollector;
use Infrastructure\Service\Collector\MiddlewareCollector;
use Infrastructure\Service\Renderer\TwigRenderer;

return [
    'base_path' => \dirname(__DIR__, 3),
    'app_path' => \dirname(__DIR__, 2),
    \Psr\Log\LoggerInterface::class => function (ContainerInterface $c) {
        $filename = getenv('APP_ENV') === 'dev' ? 'dev.log' : 'prod.log';

        $logger = new \Monolog\Logger('API_LOG');
        $file_handler = new \Monolog\Handler\RotatingFileHandler(
            $c->get('settings.logger.path').'/'.getenv('APP_ENV').'/'.$filename,
            7
        );
        $logger->pushHandler($file_handler);
        return $logger;
    },
    'logger' => function(ContainerInterface $c) {
        return $c->get(\Psr\Log\LoggerInterface::class);
    },
    'errorHandler' => function (ContainerInterface $c) {
        return function (ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, Exception $e) use ($c) {
            $trace = $e->getTrace();

            $data = [
                'file' => $e->getFile() . ': '. $e->getLine(),
                'route' => $request->getMethod(). ' ' . $request->getUri()->getPath(),
                'actionClass' => $trace[0],
                'contextClass' => $trace[1],
            ];

            $body = $request->getParsedBody();
            if (!empty($body)) {
                $data['payload'] = $body;
            }

            if ($e instanceof \Infrastructure\Exception\ApiException) {
                return (new \Slim\Http\Response())->withJson($e->getDetails(), $e->getStatus());
            }

            $message = $e->getMessage();

            $c->get('logger')->addCritical($message, $data);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control, Expires')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
                ->withHeader('Access-Control-Max-Age', '604800')
                ->withJson(['message' => $e->getMessage()], 500);
        };
    },
    RouteCollector::class => function (ContainerInterface $c) {
        $routeCollector = new RouteCollector();

        $routeCollector->addDefinition($c->get('app_path').'/App/config/routes.php');

        $pluginCollector = $c->get(PluginCollector::class);

        /** @var \Infrastructure\Plugin\Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $routeCollector->addDefinition($plugin->getConfigPath().'/routes.php');
        }

        return $routeCollector;
    },
    PluginCollector::class => function (ContainerInterface $c) {
        $pluginCollector = new PluginCollector();
        $pluginCollector->addDefinition($c->get('app_path').'/App/config/plugins.php');
        return $pluginCollector;
    },
    DatasourceCollector::class => function (ContainerInterface $c) {
        $dsCollector = new DatasourceCollector();
        $pluginCollector = $c->get(PluginCollector::class);

        $dsCollector->addDefinition(\dirname(__DIR__, 2).'/config/datasources.php');
        /** @var \Infrastructure\Plugin\Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $dsCollector->addDefinition($plugin->getDatasources());
        }

        return $dsCollector;
    },
    MiddlewareCollector::class => function (ContainerInterface $c) {
        $mwCollector = new MiddlewareCollector();
        $pluginCollector = $c->get(PluginCollector::class);

        /** @var \Infrastructure\Plugin\Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $mwCollector->addDefinition($plugin->getMiddlewares());
        }

        return $mwCollector;
    },
    TwigRenderer::class => function(ContainerInterface $c) {
        $templates = [$c->get('theme') => $c->get('base_path').'/themes/'.$c->get('theme')];

        $pluginCollector = $c->get(PluginCollector::class);
        /** @var \Infrastructure\Plugin\Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $templates[$plugin->getName()] = $plugin->getTemplatePath();
        }

        $assets = new \Stolz\Assets\Manager([
            'pipeline' => getenv('APP_ENV') !== 'dev',
            'public_dir' => $c->get('base_path').'/public',
            'js_dir' => 'assets/js'
        ]);

        return new TwigRenderer(
            $templates,
            ['cache' => $c->get('base_path').'/var/cache', 'debug' => true],
            new \Infrastructure\Service\Renderer\AssetExtension($c->get('request')->getUri(), $pluginCollector, $assets)
        );
    },
    PDO::class => function (ContainerInterface $c) {
        return new PDO('sqlite:'.$c->get('base_path').'/var/sqlite');
    },
    \Atlas\Orm\AtlasContainer::class => function (ContainerInterface $c) {
        $atlasContainer = new \Atlas\Orm\AtlasContainer($c->get(PDO::class));

        $atlasContainer->setMappers($c->get(DatasourceCollector::class)->getDataSources());

        return $atlasContainer;
    },
    \Atlas\Orm\Atlas::class => function (ContainerInterface $c) {
        return $c->get(\Atlas\Orm\AtlasContainer::class)->getAtlas();
    },
];