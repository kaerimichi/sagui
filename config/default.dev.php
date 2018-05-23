<?php

use Monolog\Logger;

return [
    'theme' => 'default',
    'env' => getenv('APP_ENV'),
    'prefix' => '/api/v1',

    'settings.debug' => true,
    'settings.displayErrorDetails' => true,
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => true,
    'settings.logger.name' => 'App',
    'settings.logger.level' => Logger::DEBUG,
    'settings.logger.path' => dirname(__DIR__).'/var/logs',

    'sqlite_path' => getenv('SQLLITE_PATH'),
];