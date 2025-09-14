<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result;

readonly class ProductView
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $thumbnail,
        public float $price,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
        ];
    }
}