<?php
declare(strict_types=1);

namespace Plugin\Admin\Controller;

use Infrastructure\Controller\AbstractController;
use Plugin\Admin\Datasource\User\UserMapper;

class FrontendController extends AbstractController
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $existsAdmin = $this->atlas->select(UserMapper::class)->fetchCount() > 0;

        if (!$existsAdmin) {
            return $this->render('@admin/register-admin.html.twig');
        }

        return $this->render('@admin/index.html.twig');
    }
}