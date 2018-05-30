<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class InvalidSessionException extends ApiException
{
    /**
     * InvalidSessionException constructor.
     * @param string $message
     * @param string $hint
     * @param int $status
     */
    public function __construct(string $message, string $hint = '', int $status = 401)
    {
        parent::__construct($message, $hint, $status);
    }

    public function exceptionDesc(): string
    {
        return "The session is invalid.";
    }

}