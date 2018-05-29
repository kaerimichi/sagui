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
     * @var array
     */
    private $config = [
        'config_path' => '/config',
        'template_path' => '/themes',
        'datasources' => 'datasources.php',
        'dependencies' => 'dependencies.php',
        'routes' => 'routes.php',
        'middlewares' => 'middlewares.php'
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
     * @param array $config
     */
    public function config(array $config): void
    {
        $this->config = array_merge($this->config, $config);
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
            $this->templatePath = $this->getPath().$this->config['template_path'];
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
            $this->configPath = $this->getPath().$this->config['config_path'];
        }

        return $this->configPath;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getDatasources(): array
    {
        if (!$this->datasources) {
            $this->datasources = include_once $this->getConfigPath().'/'.$this->config['datasources'];
        }

        return $this->datasources;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getDependencies(): array
    {
        if (!$this->dependencies) {
            $this->dependencies = include_once $this->getConfigPath().'/'.$this->config['dependencies'];
        }

        return $this->dependencies;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getRoutes(): array
    {
        if (!$this->routes) {
            $this->routes = include_once $this->getConfigPath().'/'.$this->config['routes'];
        }

        return $this->routes;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getMiddlewares(): array
    {
        if (!$this->middlewares) {
            $this->middlewares = include_once $this->getConfigPath().'/'.$this->config['middlewares'];
        }

        return $this->middlewares;
    }
}