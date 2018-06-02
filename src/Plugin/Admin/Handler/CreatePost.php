<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

use Infrastructure\Exception\NotFoundException;
use Infrastructure\Form\Form;
use Infrastructure\Form\FormPersistHelper;
use Infrastructure\Service\Search\SimpleSearch;
use Plugin\Admin\Datasource\Post\PostMapper;
use Plugin\Admin\Datasource\User\UserMapper;

class CreatePost
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
     * @var SimpleSearch
     */
    private $finder;

    /**
     * CreatePost constructor.
     * @param FormPersistHelper $helper
     * @param Form $form
     * @param SimpleSearch $finder
     */
    public function __construct(FormPersistHelper $helper, Form $form, SimpleSearch $finder)
    {
        $this->helper = $helper;
        $this->form = $form;
        $this->finder = $finder;
    }

    /**
     * @param string $title
     * @param string $body
     * @param array $tags
     * @param string $email
     * @return array
     * @throws NotFoundException
     * @throws \Infrastructure\Exception\HandlerException
     * @throws \Infrastructure\Exception\ValidationException
     */
    public function __invoke(string $title, string $body, array $tags, string $email): array
    {
        $user = $this->finder->findOneBy(UserMapper::class, ['email = ?' => $email]);
        if (!$user) {
            throw new NotFoundException('no_user_was_found', 'You need to supply  existing user.');
        }

        $this->form
            ->field('user_id', (int) $user['id'])->rules(['intType', 'positive'])
            ->field('title', $title)->rules(['length' => [4, 255]])
            ->field('body', $body)->rules(['notEmpty'])
            ->field('tags', $tags)->rules(['arrayType'])
            ->field('published', false);

        $record = $this->helper
            ->form($this->form)
            ->mapper(PostMapper::class)
            ->register();

        return $record->getArrayCopy();
    }
}