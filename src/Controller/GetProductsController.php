<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Exceptions\UseCaseException;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries\GetProductByCategoryQuery;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries\GetProductByCategoryQueryHandler;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries\Result\ProductsView;
use Throwable;

class GetProductsController extends Controller
{
    public function __construct(
        private readonly ProductsView $productsVew
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        $rawRequest = json_decode($request->getBody()->getContents(), true);

        $response->getBody()->write(
            json_encode(
                $this->productsVew->toArray($rawRequest['category']),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }

    public function getByCategory(
        RequestInterface $request,
        GetProductByCategoryQueryHandler $getProductByCategory
    ): ResponseInterface {
        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);

            $category = $rawRequest['category'] ?? null;

            $result = $getProductByCategory(new GetProductByCategoryQuery($category));

            if ($category === null) {
                return $this->createResponse([
                    'status' => 'error',
                    'message' => 'Не указана искомая категория',
                ], 429);
            }

        } catch (UseCaseException) {
            return $this->createResponse([
                'status' => 'error',
                'message' => 'Не удалось получить продукты по категории, обратитесь в поддержку',
            ], 500);
        }

        return $this->createResponse([
            'status' => 'success',
            'products' => array_map(static fn (ProductsView $product) => $product->toArray(), $result),
        ]);

    }
}
