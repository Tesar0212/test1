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
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

class AddToCartController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CartView $cartView,
        private readonly CartManager $cartManager,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        $cart = $this->cartManager->getCart();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->toArray($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
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
