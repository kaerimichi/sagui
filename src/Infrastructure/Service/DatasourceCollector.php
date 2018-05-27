<?php
declare(strict_types=1);

namespace Infrastructure\Service;

use Infrastructure\Plugin\PluginInterface;

class DatasourceCollector
{
    /**
     * @var array
     */
    private $dataSources;

    /**
     * @param PluginCollector $pluginCollector
     * @return array
     */
    public function collect(PluginCollector $pluginCollector): void
    {
        $this->dataSources = [[]];
        if (is_file(\dirname(__DIR__, 2).'/config/App/datasources.php')) {
            $this->dataSources = [include \dirname(__DIR__, 2).'/config/App/datasources.php'];
        }

        /** @var PluginInterface $plugin */
        foreach ($pluginCollector->getIterator() as $plugin) {
            if (!is_file($plugin->getConfigPath().'/datasources.php')) {
                continue;
            }

            $pluginDs = include $plugin->getConfigPath().'/datasources.php';
            $this->dataSources[] = $pluginDs;
        }

        $this->dataSources = array_merge(...$this->dataSources);
    }

    /**
     * @return array
     */
    public function getDataSources(): ?array
    {
        return $this->dataSources;
    }
}