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
    ];

    /**
     * @var string
     */
    private $datasources;

    /**
     * @var string
     */
    private $dependencies;

    /**
     * @var string
     */
    private $routes;

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
     * @return string
     * @throws \ReflectionException
     */
    public function getDatasources(): string
    {
        if (!$this->datasources) {
            $this->datasources = $this->getConfigPath().'/'.$this->config['datasources'];
        }

        return $this->datasources;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getDependencies(): string
    {
        if (!$this->dependencies) {
            $this->dependencies = $this->getConfigPath().'/'.$this->config['dependencies'];
        }

        return $this->dependencies;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getRoutes(): string
    {
        if (!$this->routes) {
            $this->routes = $this->getConfigPath().'/'.$this->config['routes'];
        }

        return $this->routes;
    }
}