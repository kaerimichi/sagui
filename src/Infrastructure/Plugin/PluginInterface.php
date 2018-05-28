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
     * @return string
     */
    public function getDatasources(): string;

    /**
     * @return string
     */
    public function getDependencies(): string;

    /**
     * @return string
     */
    public function getRoutes(): string;
}