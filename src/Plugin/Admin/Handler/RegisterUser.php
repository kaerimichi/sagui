<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

use Atlas\Orm\Atlas;
use Infrastructure\Exception\HandlerException;
use Infrastructure\Exception\ValidationException;
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
     * @var UserForm
     */
    private $userForm;

    /**
     * @var Atlas
     */
    private $atlas;

    /**
     * RegisterUser constructor.
     * @param FormPersistHelper $helper
     * @param UserForm $userForm
     * @param Atlas $atlas
     */
    public function __construct(FormPersistHelper $helper, UserForm $userForm, Atlas $atlas)
    {
        $this->helper = $helper;
        $this->userForm = $userForm;
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
            throw new HandlerException('email_not_available', 'Another user has this email.');
        }

        $encodePwd = password_hash($password, PASSWORD_ARGON2I);
        $record = $this->helper
            ->mapper(UserMapper::class)
            ->form($this->userForm)
            ->register(['name' => $name, 'email' => $email, 'password' => $encodePwd]);

        return $record->getArrayCopy();
    }
}