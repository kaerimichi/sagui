<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

use Atlas\Orm\Atlas;
use Infrastructure\Exception\HandlerException;
use Infrastructure\Exception\ValidationException;
use Infrastructure\Form\Form;
use Infrastructure\Form\FormPersistHelper;
use Plugin\Admin\Datasource\User\UserMapper;
use Plugin\Admin\Handler\Form\UserForm;

class RegisterUser
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
     * RegisterUser constructor.
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
     * @param string $name
     * @param string $email
     * @param string $password
     * @return array
     * @throws HandlerException
     * @throws ValidationException
     */
    public function __invoke(string $name, string $email, string $password): array
    {
        $user = $this->atlas
            ->select(UserMapper::class)
            ->where('email = ?', $email)
            ->fetchRecord();

        if ($user) {
            throw new HandlerException('email_not_available', 'Try with another email.');
        }

        $this->form
            ->field('name', $name)->rules(['length' => [4, 100]])
            ->field('password_clean_text', $password)->rules(['length' => [4, 100]])
            ->field('password', password_hash($password, PASSWORD_ARGON2I))
            ->field('email', $email)->rules(['email']);

        $record = $this->helper
            ->mapper(UserMapper::class)
            ->form($this->form)
            ->register();

        return $record->getArrayCopy();
    }
}