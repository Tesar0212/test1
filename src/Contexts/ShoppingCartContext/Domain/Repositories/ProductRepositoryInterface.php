<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Exceptions\ConnectorException;

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