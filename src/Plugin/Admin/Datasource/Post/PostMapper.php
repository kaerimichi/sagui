<?php
namespace Plugin\Admin\Datasource\Post;

use Atlas\Orm\Mapper\AbstractMapper;
use Plugin\Admin\Datasource\User\UserMapper;

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
        $this->manyToOne('author', UserMapper::CLASS)
            ->on([
                'user_id' => 'id',
            ]);
    }
}
