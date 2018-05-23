<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\RequestInterface;

class ApiController
{
    public function createPost(RequestInterface $request): void
    {
        echo 1;exit;
    }
}