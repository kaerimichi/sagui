<?php
declare(strict_types=1);

namespace Infrastructure\Controller;

use Infrastructure\Service\Renderer\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class AbstractController
{
    /**
     * @var TwigRenderer
     */
    private $renderer;

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
}