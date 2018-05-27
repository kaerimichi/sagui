<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

abstract class ApiException extends \Exception
{
    /**
     * @var string
     */
    private $hint;

    /**
     * @var int
     */
    private $status;

    /**
     * ApiException constructor.
     * @param string $message
     * @param string $hint
     * @param int $status
     */
    public function __construct(string $message, string $hint = '', int $status = 500)
    {
        $this->message = $message;
        $this->hint = $hint;
        $this->status = $status;

        parent::__construct($message);
    }

    /**
     * @return string
     */
    abstract public function exceptionDesc(): string;

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function getDetails(): array
    {
        return [
            'general' => $this->exceptionDesc(),
            'message' => $this->message,
            'hint' => $this->hint,
        ];
    }
}