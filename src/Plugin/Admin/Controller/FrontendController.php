<?php
declare(strict_types=1);

namespace Plugin\Admin\Controller;

use Infrastructure\Controller\AbstractController;
use Plugin\Admin\Datasource\User\UserMapper;
use Psr\Http\Message\ResponseInterface;

class FrontendController extends AbstractController
{
    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(ResponseInterface $response)
    {
        if ($this->atlas->select(UserMapper::class)->fetchCount() === 0) {
            return $response->withRedirect('/admin/register');
        }

        return $this->render('@admin/index.html.twig');
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function register(ResponseInterface $response)
    {
        if ($this->atlas->select(UserMapper::class)->fetchCount() > 0) {
            return $response->withRedirect('/admin/login');
        }

        return $this->render('@admin/register-admin.html.twig');
    }
}