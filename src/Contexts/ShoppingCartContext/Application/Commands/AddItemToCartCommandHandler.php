<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Commands;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Exceptions\UseCaseException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Entities\CartItem;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidUuidException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories\CartRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Logger\LoggerInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Uuid\UuidGeneratorInterface;

readonly class AddItemToCartCommandHandler
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private UuidGeneratorInterface  $uuidGenerator,
        private LoggerInterface         $logger
    )
    {
    }

    /**
     * @throws UseCaseException
     */
    public function __invoke(AddItemToCartCommand $command): void
    {
        //'Не удалось добавить товар в корзину, обратитесь в поддержку'
        try {
            try {
                $cart = $this->cartRepository->find($command->cartUuid);

                $cartItemUid = $this->uuidGenerator->generate();
                $cartItem = new CartItem(
                    uuid: $cartItemUid,
                    productUuid: $command->productUuid,
                    quantity: $command->quantity
                );
            } catch (InvalidUuidException $exception) {
                $this->logger->error(
                    $exception->getMessage(),
                    [
                        'cartItemUuid' => $cartItemUid,
                        'productUuid' => $command->productUuid
                    ]
                );
                throw $exception;
            } catch (NegativeQuantityException $exception) {
                $this->logger->error(
                    $exception->getMessage(),
                    ['quantity' => $command->quantity]
                );
                throw $exception;
            }

            $cart->addItem($cartItem);

            $this->cartRepository->save($cart);
        } catch (\Throwable $exception) {
            throw new UseCaseException($exception->getMessage(), previous: $exception);
        }
    }
}