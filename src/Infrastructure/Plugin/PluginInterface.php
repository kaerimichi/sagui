<?php
declare(strict_types=1);

namespace Infrastructure\Plugin;

interface PluginInterface
{
    /**
     * @return string
     */
    public function getAlias(): string;

    /**
     * @param array $config
     */
    public function pathConfig(array $config): void;

    /**
     * @return array
     */
    public function getConfigTemplate(): array;

    /**
     * @return string
     */
    public function getPath(): string;

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

    /**
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * @return array
     */
    public function getMiddlewares(): array;

    /**
     * @param Configuration $config
     */
    public function setConfig(Configuration $config): void;

    /**
     * @return Configuration|null
     */
    public function getConfig(): ?Configuration;
}
