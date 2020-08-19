<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

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

    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
 *     )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response->json([
            'user' => [
                'id' => $request->getAttribute('oauth_user_id')
            ]
        ]);
    }
}