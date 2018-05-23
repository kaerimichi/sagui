<?php
declare(strict_types=1);

namespace Plugin\Admin\Controller;

use Infrastructure\Controller\AbstractController;

class FrontendController extends AbstractController
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}