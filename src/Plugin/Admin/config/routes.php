<?php

return [
    'GET@/admin' => \Plugin\Admin\Controller\FrontendController::class.'::index',
    'POST@/admin/create' => \Plugin\Admin\Controller\ApiController::class.'::create',
    'POST@/admin/login' => \Plugin\Admin\Controller\ApiController::class.'::login',
];
