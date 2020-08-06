<?php

declare(strict_types=1);

namespace App\Http\Action\Auth;

use Exception;
use Framework\Http\Psr7\ResponseFactory;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OAuthAction implements RequestHandlerInterface
{
    private AuthorizationServer $server;
    private ResponseFactory $response;

    /**
     * OAuthAction constructor.
     * @param AuthorizationServer $server
     * @param ResponseFactory $response
     */
    public function __construct(AuthorizationServer $server, ResponseFactory $response)
    {
        $this->server = $server;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->server->respondToAccessTokenRequest($request, $this->response->simple());
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($this->response->simple());
        } catch (Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($this->response->simple());
        }
    }
}