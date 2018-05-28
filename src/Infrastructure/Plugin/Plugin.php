<?php
declare(strict_types=1);

namespace Infrastructure\Plugin;

abstract class Plugin implements PluginInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var string
     */
    protected $configPath;

    /**
     * @throws \ReflectionException
     */
    public function getPath(): string
    {
        if ($this->path) {
            return $this->path;
        }

        $this->path = \dirname((new \ReflectionClass($this))->getFileName());
        return $this->path;
    }

    /**
     * @param string $templatePath
     * @throws \ReflectionException
     */
    public function setTemplatePath(string $templatePath)
    {
        $this->templatePath = $this->getPath().$templatePath;
    }

    /**
     * @param string $configPath
     * @throws \ReflectionException
     */
    public function setConfigPath(string $configPath)
    {
        $this->configPath = $this->getPath().$configPath;
    }

    /**
     * @return string
     */
    public function getDefaultTemplatePath():string
    {
        return '/themes';
    }

    /**
     * @return string
     */
    public function getDefaultConfigPath(): string
    {
        return '/config';
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->configPath;
    }
}