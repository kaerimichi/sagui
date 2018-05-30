<?php
declare(strict_types=1);

namespace Plugin\Admin\Controller;

use Aura\Auth\Auth;
use Infrastructure\Controller\AbstractController;
use Plugin\Admin\Handler\LoginUser;
use Plugin\Admin\Handler\RegisterUser;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class ApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param RegisterUser $registerUser
     * @return ResponseInterface
     * @throws \Infrastructure\Exception\HandlerException
     * @throws \Infrastructure\Exception\ValidationException
     */
    public function create(Request $request, RegisterUser $registerUser)
    {
        $res = $registerUser(
            $request->getParam('name'),
            $request->getParam('email'),
            $request->getParam('password')
        );

        return $this->renderJson($res, 200);
    }

    /**
     * @param Request $request
     * @param LoginUser $loginUser
     * @param Auth $auth
     * @return ResponseInterface
     * @throws \Infrastructure\Exception\InvalidLoginException
     */
    public function login(Request $request, LoginUser $loginUser, Auth $auth)
    {
        $loginUser($request->getParam('email'), $request->getParam('password'));
        return $this->renderJson([], 204);
    }
}