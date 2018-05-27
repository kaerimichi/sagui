<?php
declare(strict_types=1);

namespace Infrastructure\Controller;

use Infrastructure\Service\Renderer\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class AbstractController
{
    /**
     * AbstractController constructor.
     * @param TwigRenderer $renderer
     */
    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param string $template
     * @param array $data
     * @return ResponseInterface
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $template, array $data = []): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->renderer->render($template, $data));
        return $response;
    }

    /**
     * @param array|null $data
     * @param int $status
     * @return ResponseInterface
     */
    protected function renderJson(?array $data, int $status = 200): ResponseInterface
    {
        $response = new Response();
        $response = $response->withJson($data, $status);
        return $response;
    }
}