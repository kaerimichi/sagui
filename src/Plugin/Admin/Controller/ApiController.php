<?php
declare(strict_types=1);

namespace Plugin\Admin\Controller;

use Infrastructure\Controller\AbstractController;
use Plugin\Admin\Handler\CreatePost;
use Plugin\Admin\Handler\LoginUser;
use Plugin\Admin\Handler\RegisterUser;
use Plugin\Admin\Handler\UpdatePost;
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
     * @return ResponseInterface
     * @throws \Infrastructure\Exception\InvalidLoginException
     */
    public function login(Request $request, LoginUser $loginUser)
    {
        $loginUser($request->getParam('email'), $request->getParam('password'));
        return $this->renderJson([], 204);
    }

    /**
     * @param Request $request
     * @param CreatePost $createPost
     * @return ResponseInterface
     * @throws \Infrastructure\Exception\HandlerException
     * @throws \Infrastructure\Exception\NotFoundException
     * @throws \Infrastructure\Exception\ValidationException
     */
    public function createPost(Request $request, CreatePost $createPost)
    {
        $res = $createPost(
            $request->getParam('title'),
            $request->getParam('body'),
            $request->getParam('tags', []),
            $this->getAuth()->getUserName()
        );

        return $this->renderJson($res, 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param UpdatePost $updatePost
     * @return ResponseInterface
     * @throws \Infrastructure\Exception\HandlerException
     * @throws \Infrastructure\Exception\NotFoundException
     * @throws \Infrastructure\Exception\ValidationException
     */
    public function updatePost(int $id, Request $request, UpdatePost $updatePost)
    {
        $res = $updatePost(
            $id,
            $request->getParam('title'),
            $request->getParam('body'),
            $request->getParam('tags', []),
            $request->getParam('published')
        );

        return $this->renderJson($res, 200);
    }
}
