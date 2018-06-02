<?php

return [
    'GET@/admin' => \Plugin\Admin\Controller\FrontendController::class.'::index',
    'GET@/admin/register' => \Plugin\Admin\Controller\FrontendController::class.'::register',

    'POST@/admin/create' => \Plugin\Admin\Controller\ApiController::class.'::create',
    'POST@/admin/authorize' => \Plugin\Admin\Controller\ApiController::class.'::login',

    'POST@/admin/posts' => \Plugin\Admin\Controller\ApiController::class.'::createPost',
    'PUT@/admin/posts/{id}' => \Plugin\Admin\Controller\ApiController::class.'::updatePost',
    'GET@/admin/posts' => \Plugin\Admin\Controller\ApiController::class.'::paginatePosts',
];
