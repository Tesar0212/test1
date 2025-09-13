<?php

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Entities\CartItem;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidCartItemException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories\CartRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Interfaces\ConnectorInterface;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;

readonly class RedisCartRepository implements CartRepositoryInterface
{
    public function __construct(
        private ConnectorInterface $connector,
    )
    {
    }

    /**
     * @throws InvalidCartItemException
     * @throws ConnectorException
     * @throws NegativeQuantityException
     */
    public function find(string $uuid): Cart
    {
        $cart = $this->connector->get($uuid);

        if (empty($cart)) {
            return new Cart(session_id(), []);
        }

        return new Cart($uuid, array_map(static fn(array $cartItem) => new CartItem(
            uuid: (string)$cartItem['uuid'],
            productUuid: (string)$cartItem['productUuid'],
            quantity: (int)$cartItem['quantity'],
        ), $cart));
    }

    /**
     * @throws ConnectorException
     */
    public function save(Cart $cart): void
    {
        $this->connector->set($cart->getUuid(), $cart);
    }
}