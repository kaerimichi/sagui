<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\AuthFactory;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Auth;

return [
    PdoAdapter::class => function (ContainerInterface $c) {
        $hash = new \Aura\Auth\Verifier\PasswordVerifier(PASSWORD_ARGON2I);
        $cols = ['email', 'password', 'name',];
        $from = 'users';
        $where = '';
        return $c->get(AuthFactory::class)->newPdoAdapter($c->get(PDO::class), $hash, $cols, $from, $where);
    },
    AuthFactory::class => function () {
        return new AuthFactory($_COOKIE);
    },
    LoginService::class => function (ContainerInterface $c) {
        return $c->get(AuthFactory::class)->newLoginService($c->get(PdoAdapter::class));
    },
    LogoutService::class => function (ContainerInterface $c) {
        return $c->get(AuthFactory::class)->newLogoutService($c->get(PdoAdapter::class));
    },
    ResumeService::class => function (ContainerInterface $c) {
        return $c->get(AuthFactory::class)->newResumeService($c->get(PdoAdapter::class));
    },
    Auth::class => function (ContainerInterface $c) {
        return $c->get(AuthFactory::class)->newInstance();
    }
];