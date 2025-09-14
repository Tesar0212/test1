<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result;

readonly class CartItemView
{
    public function __construct(
        public string $uuid,
        public int $quantity,
        public float $total,
        public ProductView $product
    )
    {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'product' => $this->product->toArray()
        ];
    }
}