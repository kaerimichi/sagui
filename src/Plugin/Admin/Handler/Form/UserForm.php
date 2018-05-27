<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler\Form;

use Infrastructure\Form\Form;

class UserForm extends Form
{
    public function check(array $data): bool
    {
        return $this
            ->field('name', $data['name'])->rules(['length' => [4, 100]])
            ->field('password', $data['password'])->rules(['length' => [4, 100]])
            ->field('email', $data['email'])->rules(['email'])
            ->validate();
    }
}