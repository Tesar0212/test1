<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Commands\AddItemToCartCommand;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Commands\AddItemToCartCommandHandler;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Exceptions\UseCaseException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\GetCartQuery;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\GetCartQueryHandler;
use Raketa\BackendTestTask\Controller\Common\Controller;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{
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

    public function addItemToCart(
        RequestInterface $request,
        AddItemToCartCommandHandler $command,
        GetCartQueryHandler $getCartQuery,
    ): ResponseInterface {
        $cartUuid = session_id();
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $commandData = new AddItemToCartCommand(
            cartUuid: $cartUuid,
            productUuid: $rawRequest['productUuid'],
            quantity: $rawRequest['quantity']
        );

        try {
            $command($commandData); // Выполняем добавление

            $cartQueryResult = $getCartQuery(
                new GetCartQuery(cartUuid: $cartUuid)
            ); //Получаем корзину
        } catch (UseCaseException) {
            return $this->createResponse([
                'status' => 'error',
                'message' => 'Не удалось добавить товар в корзину, обратитесь в поддержку'
            ], 500);
        }

        return $this->createResponse([
            'status' => 'success',
            'cart' => $cartQueryResult->toArray()
        ]);
    }
}
