<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class PersistenceException extends ApiException
{
    /**
     * PersistenceException constructor.
     * @param string $msg
     * @param \Exception|null $atlasException
     */
    public function __construct(string $msg, ?\Exception $atlasException)
    {
        $hint = $atlasException ? $atlasException->getMessage() : 'Persistence error!';
        parent::__construct($msg, $hint, 500);
    }

    public function exceptionDesc(): string
    {
        return 'The persistence storage thrown an exception.';
    }
}