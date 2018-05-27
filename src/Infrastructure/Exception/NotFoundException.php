<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class NotFoundException extends ApiException
{
    public function exceptionDesc(): string
    {
        return 'resource_not_found';
    }
}