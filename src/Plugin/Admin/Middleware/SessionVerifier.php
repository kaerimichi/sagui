<?php
declare(strict_types=1);

namespace Plugin\Admin\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Infrastructure\Exception\InvalidSessionException;
use Infrastructure\Service\Collector\PluginCollector;
use Plugin\Admin\Admin;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Slim\Route;

class SessionVerifier
{
    /**
     * @var ResumeService
     */
    private $resumeService;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Admin
     */
    private $admin;

    /**
     * SessionVerifier constructor.
     * @param ResumeService $resumeService
     * @param Auth $auth
     * @param PluginCollector $collector
     */
    public function __construct(ResumeService $resumeService, Auth $auth, PluginCollector $collector)
    {
        $this->resumeService = $resumeService;
        $this->auth = $auth;
        $this->admin = $collector->find(Admin::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     * @throws InvalidSessionException
     */
    public function __invoke(ServerRequestInterface $request, Response $response, callable $next)
    {
        $this->resumeService->resume($this->auth);

        /** @var Route $route */
        $route = $request->getAttributes()['route'];

        $config = $this->admin->getConfig();
        $whitelistUrl = $config['session_whitelist'];
        foreach ($whitelistUrl as $url) {
            if ($route && $route->getPattern() === $url) {
                return $next($request, $response);
            }
        }

        if (!$this->auth->isValid() || $this->auth->isExpired()) {
            throw new InvalidSessionException(
                'invalid_session',
                'Your Session has expired. You need to login first.'
            );
        }

        return $next($request, $response);
    }
}