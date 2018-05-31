<?php

return [
    'GET@/admin' => \Plugin\Admin\Controller\FrontendController::class.'::index',
    'GET@/admin/register' => \Plugin\Admin\Controller\FrontendController::class.'::register',
    'POST@/admin/create' => \Plugin\Admin\Controller\ApiController::class.'::create',
    'POST@/admin/authorize' => \Plugin\Admin\Controller\ApiController::class.'::login',
    'POST@/admin/posts/create' => \Plugin\Admin\Controller\ApiController::class.'::createPost',
];
