<?php
declare(strict_types=1);

namespace Infrastructure\Service;

use Infrastructure\Exception\FileNotFoundException;

class Utils
{
    /**
     * @param string $file
     * @param bool $throwException
     * @return array|null
     * @throws FileNotFoundException
     */
    public static function loadConfigFile(string $file, bool $throwException = false): array
    {
        if (is_file($file)) {
            return include $file;
        }

        if ($throwException) {
            throw new FileNotFoundException('file_not_found', 'Could not load file :'. $file);
        }

        return [];
    }
}