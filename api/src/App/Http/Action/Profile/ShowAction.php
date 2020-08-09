<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ShowAction implements RequestHandlerInterface
{
    private ResponseFactory $response;

    /**
     * ShowAction constructor.
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response->json([
            'id' => $request->getAttribute('oauth_user_id'),
        ]);
    }
}