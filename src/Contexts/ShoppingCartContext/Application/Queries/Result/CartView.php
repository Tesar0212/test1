<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result;

readonly class CartView
{
    /**
     * @param string $uuid
     * @param string $paymentMethod
     * @param CartItemView[] $items
     * @param CustomerView $customer
     * @param float $total
     */
    public function __construct(
        public string $uuid,
        public string $paymentMethod,
        public array $items,
        public CustomerView $customer,
        public float $total
    )
    {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'paymentMethod' => $this->paymentMethod,
            'items' => array_map(fn (CartItemView $cartItemView) => $cartItemView->toArray(), $this->items),
            'customer' => $this->customer->toArray(),
            'total' => $this->total,
        ];
    }
}