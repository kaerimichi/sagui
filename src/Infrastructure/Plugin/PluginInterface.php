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
     * @param string $templatePath
     */
    public function setTemplatePath(string $templatePath);

    /**
     * @param string $configPath
     */
    public function setConfigPath(string $configPath);

    /**
     * @return string
     */
    public function getDefaultTemplatePath():string;

    /**
     * @return string
     */
    public function getDefaultConfigPath(): string;

    /**
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * @return string
     */
    public function getConfigPath(): string;
}