<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidCartItemException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidUuidException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Exceptions\ConnectorException;

interface CartRepositoryInterface
{
    /**
     * @throws InvalidCartItemException
     * @throws ConnectorException
     * @throws NegativeQuantityException
     * @throws InvalidUuidException
     */
    public function find(string $uuid): Cart;

    /**
     * @throws ConnectorException
     */
    public function save(Cart $cart): void;

    /**
     * @param string $uuid
     * @return array{
     *     uuid: string,
     *     item: array,
     *     paymentMethod: string
     * }
     * @throws ConnectorException
     */
    public function findRaw(string $uuid): array;
}