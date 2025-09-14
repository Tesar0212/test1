<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidCartItemException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidUuidException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;

interface ProductRepositoryInterface
{
    /**
     * @param string[] $uuids
     * @return array<int, array{
     *     uuid: string,
     *     name: string,
     *     thumbnail: string,
     *     price: float
     * }>
     *
     * @throws ConnectorException
     */
    public function findRawByIds(array $uuids): array;
}