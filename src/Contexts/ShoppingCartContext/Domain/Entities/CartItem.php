<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Entities;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;

final class CartItem
{
    /**
     * @throws NegativeQuantityException
     */
    public function __construct(
        private readonly string $uuid,
        private readonly string $productUuid,
        private int $quantity,
    ) {
        $this->validate();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @throws NegativeQuantityException
     */
    public function increaseQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException();
        }

        $this->quantity += $quantity;
    }

    /**
     * @throws NegativeQuantityException
     */
    private function validate(): void
    {
        if ($this->quantity < 0) {
            throw new NegativeQuantityException();
        }
    }
}
