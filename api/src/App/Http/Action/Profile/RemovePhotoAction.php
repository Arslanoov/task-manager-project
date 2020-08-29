<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Domain\Todo\UseCase\Person\RemovePhoto\Command;
use Domain\Todo\UseCase\Person\RemovePhoto\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class RemovePhotoAction implements RequestHandlerInterface
{
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * UploadPhotoAction constructor.
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(Handler $handler, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Remove(
     *     path="/profile/upload/remove",
     *     tags={"Profile photo remove"},
     *     @OA\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *         )
     *     )
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');

        $this->handler->handle(new Command($userId));

        return $this->response->json([], 204);
    }
}
