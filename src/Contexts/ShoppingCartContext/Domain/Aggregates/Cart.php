<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidCartItemException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Entities\CartItem;

final class Cart
{
    /**
     * @param string $uuid
     * @param CartItem[] $items
     * @throws InvalidCartItemException
     */
    public function __construct(
        private readonly string $uuid,
        private array $items,
    ) {
        $this->validate();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @throws InvalidCartItemException
     */
    private function validate(): void
    {
        foreach ($this->items as $item) {
            // Проверяем на соответствие типов
            if (!($item instanceof CartItem)) {
                throw new InvalidCartItemException();
            }
        }
    }
}
