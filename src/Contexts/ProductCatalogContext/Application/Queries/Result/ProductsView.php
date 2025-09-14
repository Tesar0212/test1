<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries\Result;

readonly class ProductsView
{
    public function __construct(
        public int $id,
        public string $uuid,
        public bool $isActive,
        public string $category,
        public string $name,
        public string $description,
        public string $thumbnail,
        public float $price,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'isActive' => $this->isActive,
            'category' => $this->category,
            'name' => $this->name,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
        ];
    }
}
