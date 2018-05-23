<?php

use App\Controller\ApiController;

return [
    'POST@/posts' => ApiController::class.'::createPost',
];
