<?php

use Monolog\Logger;

return [
    'env' => getenv('APP_ENV'),
    'prefix' => '/api/v1',
    'root' => dirname(__DIR__),

    'settings.debug' => false,
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => true,
    'settings.displayErrorDetails' => false,
    'settings.logger.name' => 'App',
    'settings.logger.level' => Logger::DEBUG,
    'settings.logger.path' => dirname(__DIR__).'/var/logs/app.log',
    'settings.routerCacheFile' => dirname(__DIR__).'/var/cache/routes.cache',
];