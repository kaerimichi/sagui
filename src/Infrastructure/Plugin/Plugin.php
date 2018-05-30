<?php
declare(strict_types=1);

namespace Infrastructure\Plugin;

use Infrastructure\Service\Utils;

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
     * @var array
     */
    private $pathConfig = [
        'config_path' => '/config',
        'template_path' => '/themes',
        'datasources' => 'datasources.php',
        'dependencies' => 'dependencies.php',
        'routes' => 'routes.php',
        'middlewares' => 'middlewares.php',
        'config' => 'config.php'
    ];

    /**
     * @var array
     */
    private $datasources;

    /**
     * @var array
     */
    private $dependencies;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var array
     */
    private $middlewares;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function pathConfig(array $config): void
    {
        $this->pathConfig = array_merge($this->pathConfig, $config);
    }

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
     * @return string
     * @throws \ReflectionException
     */
    public function getTemplatePath(): string
    {
        if (!$this->templatePath) {
            $this->templatePath = $this->getPath().$this->pathConfig['template_path'];
        }

        return $this->templatePath;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getConfigPath(): string
    {
        if (!$this->configPath) {
            $this->configPath = $this->getPath().$this->pathConfig['config_path'];
        }

        return $this->configPath;
    }

    /**
     * @return array
     * @throws \Infrastructure\Exception\FileNotFoundException
     * @throws \ReflectionException
     */
    public function getDatasources(): array
    {
        if (!$this->datasources) {
            $this->datasources = Utils::loadConfigFile($this->getConfigPath().'/'.$this->pathConfig['datasources']);
        }

        return $this->datasources;
    }

    /**
     * @return array
     * @throws \Infrastructure\Exception\FileNotFoundException
     * @throws \ReflectionException
     */
    public function getDependencies(): array
    {
        if (!$this->dependencies) {
            $this->dependencies = Utils::loadConfigFile($this->getConfigPath().'/'.$this->pathConfig['dependencies']);
        }

        return $this->dependencies;
    }

    /**
     * @return array
     * @throws \Infrastructure\Exception\FileNotFoundException
     * @throws \ReflectionException
     */
    public function getRoutes(): array
    {
        if (!$this->routes) {
            $this->routes = Utils::loadConfigFile($this->getConfigPath().'/'.$this->pathConfig['routes']);
        }

        return $this->routes;
    }

    /**
     * @return array
     * @throws \Infrastructure\Exception\FileNotFoundException
     * @throws \ReflectionException
     */
    public function getMiddlewares(): array
    {
        if (!$this->middlewares) {
            $this->middlewares = Utils::loadConfigFile($this->getConfigPath().'/'.$this->pathConfig['middlewares']);
        }

        return $this->middlewares;
    }

    /**
     * @return array
     * @throws \Infrastructure\Exception\FileNotFoundException
     * @throws \ReflectionException
     */
    public function getConfig(): array
    {
        if (!$this->config) {
            $this->config = Utils::loadConfigFile($this->getConfigPath().'/'.$this->pathConfig['config']);
        }

        return $this->config;
    }
}