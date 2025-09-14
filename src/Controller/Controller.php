<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    protected function createResponse(array $data = [], int $status = 200): ResponseInterface
    {
        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status);
    }
}