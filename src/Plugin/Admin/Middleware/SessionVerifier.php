<?php
declare(strict_types=1);

namespace Plugin\Admin\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use MongoDB\Driver\Exception\AuthenticationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

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
     * SessionVerifier constructor.
     * @param ResumeService $resumeService
     * @param Auth $auth
     */
    public function __construct(ResumeService $resumeService, Auth $auth)
    {
        $this->resumeService = $resumeService;
        $this->auth = $auth;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->resumeService->resume($this->auth);
        if ($this->auth->isExpired()) {
            throw new AuthenticationException('Your login is expired.', 403);
        }

        return $next($request, $response);
    }
}