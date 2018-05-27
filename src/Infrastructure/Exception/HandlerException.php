<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class HandlerException extends ApiException
{
    public function exceptionDesc(): string
    {
        return 'Some rule in the Handler wasn\'t satisfied';
    }
}