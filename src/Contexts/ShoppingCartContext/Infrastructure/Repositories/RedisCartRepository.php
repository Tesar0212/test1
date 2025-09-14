<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Entities\CartItem;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Enums\PaymentMethodEnum;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories\CartRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Interfaces\ConnectorInterface;

readonly class RedisCartRepository implements CartRepositoryInterface
{
    public function __construct(
        private ConnectorInterface $connector,
    )
    {
    }

    public function find(string $uuid): Cart
    {
        $cart = $this->connector->get($uuid);

        $paymentMethod = PaymentMethodEnum::tryFrom($cart['payment_method'] ?? '')
            ?? PaymentMethodEnum::Cash;

        if (empty($cart)) {
            return new Cart(session_id(), [], $paymentMethod);
        }

        return new Cart(
            uuid: $uuid,
            items: array_map(static fn(array $cartItem) => new CartItem(
                uuid: (string)$cartItem['uuid'],
                productUuid: (string)$cartItem['productUuid'],
                quantity: (int)$cartItem['quantity'],
            ), $cart),
            paymentMethod: $paymentMethod,
        );
    }

    public function save(Cart $cart): void
    {
        $this->connector->set($cart->getUuid(), $cart->toArray());
    }

    public function findRaw(string $uuid): array
    {
        return $this->connector->get($uuid);
    }
}