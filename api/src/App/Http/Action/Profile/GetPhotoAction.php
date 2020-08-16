<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetPhotoAction implements RequestHandlerInterface
{
    private string $path;
    private PersonRepository $persons;
    private ResponseFactory $response;

    /**
     * GetPhotoAction constructor.
     * @param string $path
     * @param PersonRepository $persons
     * @param ResponseFactory $response
     */
    public function __construct(string $path, PersonRepository $persons, ResponseFactory $response)
    {
        $this->path = $path;
        $this->persons = $persons;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $person = $this->persons->getById(new Id($request->getAttribute('oauth_user_id')));

        return $this->response->json([
            'url' => $this->path . '/' . $person->getBackgroundPhoto()->getPath()
        ]);
    }
}