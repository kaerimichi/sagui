<?php
namespace Infrastructure\Datasource\Configuration;

use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class ConfigurationMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        // no related fields
    }
}
