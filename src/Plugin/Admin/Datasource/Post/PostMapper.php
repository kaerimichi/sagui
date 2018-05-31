<?php
namespace Plugin\Admin\Datasource\Post;

use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class PostMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        // no related fields
    }
}
