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
     */
    public function collect(PluginCollector $pluginCollector): void
    {
        $this->dataSources = [[]];
        if (is_file(\dirname(__DIR__, 2).'/config/datasources.php')) {
            $this->dataSources = [include \dirname(__DIR__, 2).'/config/datasources.php'];
        }

        /** @var PluginInterface $plugin */
        foreach ($pluginCollector->getIterator() as $plugin) {
            if (!is_file($plugin->getDatasources())) {
                continue;
            }

            $pluginDs = include $plugin->getDatasources();
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