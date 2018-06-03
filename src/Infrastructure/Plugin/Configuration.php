<?php
declare(strict_types=1);

namespace Infrastructure\Plugin;

use Atlas\Orm\Atlas;
use Atlas\Orm\Mapper\RecordInterface;
use Infrastructure\Datasource\Configuration\ConfigurationMapper;

class Configuration implements \ArrayAccess
{
    /**
     * @var array
     */
    private $configTemplate;

    /**
     * @var Atlas
     */
    private $atlas;

    /**
     * @var string
     */
    private $plugin;

    /**
     * @var array
     */
    private $config;

    /**
     * Configuration constructor.
     * @param array $configTemplate
     * @param Atlas $atlas
     * @param string $plugin
     */
    public function __construct(array $configTemplate, Atlas $atlas, string $plugin)
    {
        $this->configTemplate = $configTemplate;
        $this->atlas = $atlas;
        $this->plugin = $plugin;
    }

    /**
     * @return Configuration
     */
    public function loadConfig(): self
    {
        if ($this->config) {
            return $this;
        }

        $record = $this->atlas
            ->select(ConfigurationMapper::class, ['plugin' => $this->plugin])
            ->fetchRecord();

        if (!$record) {
            $this->config = $this->createConfig();
            return $this;
        }

        $this->config = $this->updateChanges($record);
        return $this;
    }

    /**
     * @param RecordInterface $record
     * @return array
     */
    private function updateChanges(RecordInterface $record): array
    {
        $actualConfig = json_decode($record->getArrayCopy()['data'], true);

        $actualFields = array_keys($actualConfig);
        $templateFields = array_keys($this->configTemplate);

        $newTemplateFields = array_diff($templateFields, $actualFields);
        $removedTemplateFields = array_diff($actualFields, $templateFields);

        if ($newTemplateFields) {
            foreach ($newTemplateFields as $templateField) {
                $actualConfig[$templateField] = $this->configTemplate[$templateField]['default'];
            }
        }

        if ($removedTemplateFields) {
            foreach ($removedTemplateFields as $templateField) {
                unset($actualConfig[$templateField]);
            }
        }

        $record->data = json_encode($actualConfig);
        $this->atlas->update($record);

        return $actualConfig;
    }

    /**
     * @return array
     */
    private function createConfig(): array
    {
        $data = [];
        foreach ($this->configTemplate as $name => $config) {
            $data[$name] = $config['default'];
        }

        $record = $this->atlas->newRecord(
            ConfigurationMapper::class,
            [
                'plugin' => $this->plugin,
                'data' => json_encode($data),
                'active' => true,
                'created_at' => (new \DateTime())->format('Y-m-d h:i:s')
            ]
        );
        $this->atlas->insert($record);

        return $record->getArrayCopy();
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException("Can't change config data;");
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException("Can't change config data;");
    }
}
