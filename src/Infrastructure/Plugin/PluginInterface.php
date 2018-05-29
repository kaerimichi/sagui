<?php
declare(strict_types=1);

namespace Infrastructure\Plugin;

interface PluginInterface
{
    /**
     * @return string
     */
    function getName(): string;

    /**
     * @throws \ReflectionException
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * @return string
     */
    public function getConfigPath(): string;

    /**
     * @return array
     */
    public function getDatasources(): array;

    /**
     * @return array
     */
    public function getDependencies(): array;

    /**
     * @return array
     */
    public function getRoutes(): array;
}