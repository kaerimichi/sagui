<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class ValidationException extends ApiException
{
    /**
     * @var array
     */
    private $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('validation_errors');
    }

    public function exceptionDesc(): string
    {
        return 'The validation rules wasn\'t satisfied';
    }

    public function getDetails(): array
    {
        return [
            'general' => $this->exceptionDesc(),
            'errors' => $this->errors
        ];
    }
}