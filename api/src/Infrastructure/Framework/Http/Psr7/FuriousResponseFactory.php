<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Psr7;

use Framework\Http\Psr7\ResponseFactory;
use Furious\Psr7\Response;
use Furious\Psr7\Response\EmptyResponse;
use Furious\Psr7\Response\HtmlResponse;
use Furious\Psr7\Response\JsonResponse;
use Furious\Psr7\Response\TextResponse;
use Furious\Psr7\Response\XmlResponse;
use Psr\Http\Message\ResponseInterface;

final class FuriousResponseFactory implements ResponseFactory
{
    public function html(string $html, int $code = 200): ResponseInterface
    {
        return new HtmlResponse($html, $code);
    }

    public function json(array $data, int $code = 200): ResponseInterface
    {
        return new JsonResponse($data, $code);
    }

    public function simple($data = null, int $code = 200): ResponseInterface
    {
        return new Response($code, [], $data, '1.1');
    }

    public function xml($data, int $code = 200): ResponseInterface
    {
        return new XmlResponse($data, $code);
    }

    public function text(string $text, int $code = 200): ResponseInterface
    {
        return new TextResponse($text, $code);
    }

    public function empty(): ResponseInterface
    {
        return new EmptyResponse();
    }
}
