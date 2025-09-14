<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ProductCatalogContext\Infrastructure\Repositories;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Domain\Exceptions\NotFoundProductException;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Domain\Repositories\ProductRepositoryInterface;

readonly class DBALProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Connection $connection
    )
    {
    }

    public function getByCategoryRaw(string $category): array
    {
        return $this->connection->fetchAllAssociative(
            "SELECT id FROM products WHERE is_active = 1 AND category = " . $category,
        );
    }
}
