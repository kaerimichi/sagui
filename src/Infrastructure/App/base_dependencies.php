<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Infrastructure\Service\RouteCollector;
use Infrastructure\Service\PluginCollector;
use Infrastructure\Service\TwigRenderer;

return [
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
        return function (ServerRequestInterface $request, $response, Exception $e) use ($c) {
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

        $routeCollector->addRoutesDefinition(
            \dirname(__DIR__, 2).'/App/config/routes.php',
            'default'
        );

        $pluginCollector = $c->get(PluginCollector::class);
        /**
         * @var string $name
         * @var \Infrastructure\Plugin\Plugin $plugin
         */
        foreach ($pluginCollector as $name => $plugin) {
            $routeCollector->addRoutesDefinition($plugin->getConfigPath().'/routes.php', $name);
        }

        return $routeCollector;
    },
    PluginCollector::class => function (ContainerInterface $c) {
        $pluginCollector = new PluginCollector();
        $pluginCollector->load(\dirname(__DIR__, 2).'/App/config/plugins.php');
        return $pluginCollector;
    },
    TwigRenderer::class => function(ContainerInterface $c) {
        $templates = [$c->get('theme') => \dirname(__DIR__, 3).'/themes/'.$c->get('theme')];

        $pluginCollector = $c->get(PluginCollector::class);
        /** @var \Infrastructure\Plugin\Plugin $plugin */
        foreach ($pluginCollector as $plugin) {
            $templates[$plugin->getName()] = $plugin->getTemplatePath();
        }

        return new TwigRenderer($templates, ['cache' => \dirname(__DIR__, 3).'/var/cache']);
    },
];