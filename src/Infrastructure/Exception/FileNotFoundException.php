<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

class FileNotFoundException extends ApiException
{
    public function exceptionDesc(): string
    {
        return 'The file wasn\'t found.';
    }

}