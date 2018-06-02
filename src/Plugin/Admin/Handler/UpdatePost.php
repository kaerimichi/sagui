<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

use Atlas\Orm\Atlas;
use Infrastructure\Exception\NotFoundException;
use Infrastructure\Form\Form;
use Infrastructure\Form\FormPersistHelper;
use Plugin\Admin\Datasource\Post\PostMapper;

class UpdatePost
{
    /**
     * @var FormPersistHelper
     */
    private $helper;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Atlas
     */
    private $atlas;

    /**
     * CreatePost constructor.
     * @param FormPersistHelper $helper
     * @param Form $form
     * @param Atlas $atlas
     */
    public function __construct(FormPersistHelper $helper, Form $form, Atlas $atlas)
    {
        $this->helper = $helper;
        $this->form = $form;
        $this->atlas = $atlas;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $body
     * @param array $tags
     * @param bool $published
     * @return array
     * @throws NotFoundException
     * @throws \Infrastructure\Exception\HandlerException
     * @throws \Infrastructure\Exception\ValidationException
     */
    {
        $this->form
            ->field('title', $title)->rules(['length' => [4, 255]])
            ->field('body', $body)->rules(['notEmpty'])
            ->field('tags', $tags)->rules(['arrayType'])
            ->field('published', $published);

        $record = $this->helper
            ->form($this->form)
            ->mapper(PostMapper::class)
            ->updateById($id);

        return $record->getArrayCopy();
    }
}
