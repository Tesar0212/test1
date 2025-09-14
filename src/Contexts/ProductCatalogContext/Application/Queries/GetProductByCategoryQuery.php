<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries;

readonly class GetProductByCategoryQuery
{
    public function __construct(
        public string $category
    )
    {
    }
}