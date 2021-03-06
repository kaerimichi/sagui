<?php
declare(strict_types=1);

namespace Plugin\Admin\Handler;

use Aura\Auth\Auth;
use Aura\Auth\Service\LoginService;
use Infrastructure\Exception\InvalidLoginException;
use Aura\Auth\Exception\UsernameMissing;
use Aura\Auth\Exception\PasswordMissing;
use Aura\Auth\Exception\UsernameNotFound;
use Aura\Auth\Exception\PasswordIncorrect;

class LoginUser
{
    /**
     * @var LoginService
     */
    private $loginService;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * LoginUser constructor.
     * @param LoginService $loginService
     * @param Auth $auth
     */
    public function __construct(LoginService $loginService, Auth $auth)
    {
        $this->loginService = $loginService;
        $this->auth = $auth;
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     * @throws InvalidLoginException
     */
    public function __invoke(string $email, string $password): bool
    {
        try {
            $this->loginService->login($this->auth, ['username' => $email, 'password' => $password]);
            return true;
        } catch (UsernameMissing $e) {
            throw new InvalidLoginException('username_empty', "The 'username' field is missing or empty.");
        } catch (PasswordMissing $e) {
            throw new InvalidLoginException('password_empty', "The 'password' field is missing or empty.");
        } catch (UsernameNotFound $e) {
            throw new InvalidLoginException('username_not_found', "The username you entered was not found.");
        } catch (PasswordIncorrect $e) {
            throw new InvalidLoginException('incorrect_password', "The password you entered was incorrect.");
        } catch (InvalidLoginException $e) {
            throw new InvalidLoginException('login_general_error', "Invalid login details. Please try again.");
        }
    }
}