<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

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
     * UpdatePost constructor.
     * @param FormPersistHelper $helper
     * @param Form $form
     */
    public function __construct(FormPersistHelper $helper, Form $form)
    {
        $this->helper = $helper;
        $this->form = $form;
    }

    /**
     * @param int $id
     * @param null|string $title
     * @param null|string $body
     * @param array|null $tags
     * @param bool|null $published
     * @return array
     * @throws NotFoundException
     * @throws \Infrastructure\Exception\HandlerException
     * @throws \Infrastructure\Exception\ValidationException
     */
    public function __invoke(int $id, ?string $title, ?string $body, ?array $tags, ?bool $published): array
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