<?php
declare(strict_types=1);

namespace Infrastructure\Service\Renderer;

use Infrastructure\Service\Collector\PluginCollector;
use Stolz\Assets\Manager as AssetManager;
use Slim\Http\Uri;

class AssetExtension extends \Twig_Extension
{
    /**
     * @var Uri
     */
    private $uri;

    /**
     * @var AssetManager
     */
    private $manager;

    /**
     * @var PluginCollector
     */
    private $pluginCollector;

    /**
     * AssetExtension constructor.
     * @param Uri $uri
     * @param PluginCollector $pluginCollector
     * @param AssetManager $manager
     */
    public function __construct(Uri $uri, PluginCollector $pluginCollector, AssetManager $manager)
    {
        $this->uri = $uri;
        $this->manager = $manager;
        $this->pluginCollector = $pluginCollector;
    }

    /**
     * @param string $path
     * @return string
     */
    public function assetLoader(string $path): string
    {
        if ($path[0] !== '@') {
            return $this->uri->getBaseUrl().'/'.$path;
        }

        if (strpos($path, 'http') || strpos($path, '//')) {
            return $path;
        }

        [$name] = explode('/', $path);
        $plugin = $this->pluginCollector->find(str_replace('@', '', $name));

        if (!$plugin) {
            throw new \DomainException("No plugin with alias `{$plugin}` was found.");
        }

        $fullPath = str_replace('@'.$plugin->getName(), \dirname($plugin->getTemplatePath()), $path);

        $publicPath = str_replace('@'.$plugin->getName(), '', $path);
        $publicPath = '/application/public/assets/plugins/'.$plugin->getName().$publicPath;

        if (!is_dir(\dirname($publicPath))) {
            mkdir(\dirname($publicPath), 755, true);
        }
        copy($fullPath, $publicPath);

        return str_replace(
            '@'.$plugin->getName(),
            $this->uri->getBaseUrl().'/assets/plugins/'.$plugin->getName(),
            $path
        );
    }

    /**
     * @param string $path
     */
    public function addJs(string $path): void
    {
        $this->manager->addJs($this->assetLoader($path));
    }

    /**
     * @param string $path
     */
    public function addCssAsset(string $path): void
    {
        $this->manager->addCss($this->assetLoader($path));
    }

    /**
     * @return string
     */
    public function showJs(): string
    {
        return $this->manager->js();
    }

    /**
     * @return string
     */
    public function showCss(): string
    {
        return $this->manager->css();
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('addJs', [$this, 'addJs']),
            new \Twig_SimpleFunction('addCssAsset', [$this, 'addCssAsset']),
            new \Twig_SimpleFunction('showJs', [$this, 'showJs'])
        ];
    }
}