<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Exceptions\ConnectorException;

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