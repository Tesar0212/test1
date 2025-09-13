<?php

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Interfaces;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;

interface ConnectorInterface
{
    /**
     * @throws ConnectorException
     */
    public function get(string $key): array;

    /**
     * @throws ConnectorException
     */
    public function set(string $key, Cart $value): void;

    /**
     * @throws ConnectorException
     */
    public function has(string $key): bool;
}