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
     */
    public function setTemplatePath(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $configPath
     */
    public function setConfigPath(string $configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }
}