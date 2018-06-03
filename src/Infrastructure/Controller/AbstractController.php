<?php
declare(strict_types=1);

namespace Infrastructure\Controller;

use Atlas\Orm\Atlas;
use Aura\Auth\Auth;
use Aura\Session\Session;
use Infrastructure\Service\Renderer\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class AbstractController
{
    /**
     * @var Atlas
     */
    protected $atlas;

    /**
     * @var TwigRenderer
     */
    protected $renderer;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * AbstractController constructor.
     * @param TwigRenderer $renderer
     * @param Atlas $atlas
     * @param Auth $auth
     * @param Session $session
     */
    public function __construct(TwigRenderer $renderer, Atlas $atlas, Auth $auth, Session $session)
    {
        $this->renderer = $renderer;
        $this->atlas = $atlas;
        $this->auth = $auth;
        $this->session = $session;
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

    /**
     * @return Auth
     */
    public function getAuth(): Auth
    {
        return $this->auth;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }
}