<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidCartItemException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidUuidException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;

interface CustomerRepositoryInterface
{
    /**
     * @return array{
     *     id: int,
     *     firstName: string,
     *     lastName: string,
     *     middleName: string,
     *     email: string
     * }
     * @throws ConnectorException
     */
    public function findRawByCartUuid(string $uuid): array;
}