<?php
declare(strict_types=1);

namespace Infrastructure\Controller;

class FrontendController extends AbstractController
{
    public function handler()
    {
        echo 'handler';
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function home()
    {
        $data = [];
        return $this->render('index.html.twig', $data);
    }
}