<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ProductCatalogContext\Domain\Repositories;

interface ProductRepositoryInterface
{
    /**
     * @param string $category
     * @return array<int, array{
     *     id: int,
     *     uuid: string,
     *     is_active: bool,
     *     category: string,
     *     name: string,
     *     description: string,
     *     thumbnail: string,
     *     price: float,
     * }>
     */
    public function getByCategoryRaw(string $category): array;
}