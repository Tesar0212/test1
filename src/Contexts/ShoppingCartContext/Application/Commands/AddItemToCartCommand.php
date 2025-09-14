<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Commands;

readonly class AddItemToCartCommand
{
    public function __construct(
        public string $cartUuid,
        public string $productUuid,
        public int $quantity
    )
    {
        if ($this->quantity < 0) {
            throw new \InvalidArgumentException('Quantity must be greater than 0');
        }

        if ($this->productUuid === "") {
            throw new \InvalidArgumentException('Product uuid cannot be empty');
        }

        if ($this->cartUuid === "") {
            throw new \InvalidArgumentException('Cart uuid cannot be empty');
        }
    }
}