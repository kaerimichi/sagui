<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

use Infrastructure\Exception\HandlerException;
use Infrastructure\Exception\ValidationException;
use Infrastructure\Form\Form;
use Infrastructure\Form\FormPersistHelper;
use Infrastructure\Service\Search\SimpleSearch;
use Plugin\Admin\Datasource\User\UserMapper;

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
     * @var SimpleSearch
     */
    private $finder;

    /**
     * RegisterUser constructor.
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
     * @param string $name
     * @param string $email
     * @param string $password
     * @return array
     * @throws HandlerException
     * @throws ValidationException
     */
    public function __invoke(string $name, string $email, string $password): array
    {
        $user = $this->finder->findOneBy(UserMapper::class, ['email = ?' => $email]);
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