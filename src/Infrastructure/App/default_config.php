<?php

use Monolog\Logger;

return [
    'settings.debug' => getenv('APP_ENV') === 'dev',
    'settings.displayErrorDetails' => getenv('APP_ENV') === 'dev',
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => true,
    'settings.logger.name' => 'App',
    'settings.logger.level' => Logger::DEBUG,
    'settings.logger.path' => dirname(__DIR__).'/var/logs'
];