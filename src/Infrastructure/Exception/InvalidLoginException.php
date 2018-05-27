<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class InvalidLoginException extends ApiException
{
    public function __construct(string $message, string $hint = '', int $status = 401)
    {
        parent::__construct($message, $hint, $status);
    }

    public function exceptionDesc(): string
    {
        return "The authentication failed.";
    }

}