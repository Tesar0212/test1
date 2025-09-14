<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Exceptions\UseCaseException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\GetCartQuery;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\GetCartQueryHandler;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\View\CartView;

class GetCartController extends Controller
{
    public function __construct(
        public readonly CartView $cartView,
        public readonly CartManager $cartManager
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
        $cart = $this->cartManager->getCart();

        if (! $cart) {
            $response->getBody()->write(
                json_encode(
                    ['message' => 'Cart not found'],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(404);
        } else {
            $response->getBody()->write(
                json_encode(
                    $this->cartView->toArray($cart),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
        }

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(404);
    }

    public function getCart(
        RequestInterface $request,
        GetCartQueryHandler $getCartQuery,
    ): ResponseInterface {
        try {
            $cartQueryResult = $getCartQuery(
                new GetCartQuery(cartUuid: session_id())
            );
        } catch (UseCaseException) {
            return $this->createResponse([
                'status' => 'error',
                'message' => 'Не удалось загрузить корзину, обратитесь в поддержку'
            ], 500);
        }

        return $this->createResponse([
            'status' => 'success',
            'cart' => $cartQueryResult->toArray()
        ]);
    }
}
