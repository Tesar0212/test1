<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Enums\PaymentMethodEnum;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidCartItemException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Entities\CartItem;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\InvalidUuidException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Exceptions\NegativeQuantityException;

final class Cart
{
    /**
     * @param string $uuid
     * @param CartItem[] $items
     * @param PaymentMethodEnum $paymentMethod
     * @throws InvalidCartItemException
     * @throws InvalidUuidException
     */
    public function __construct(
        private readonly string $uuid,
        private array $items,
        private PaymentMethodEnum $paymentMethod,
    ) {
        $this->validate();
    }

    /**
     * @return array{
     *     uuid: string,
     *     items: array
     * }
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'items' => array_map(static fn(CartItem $item) => $item->toArray(), $this->items),
            'payment_method' => $this->paymentMethod,
        ];
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPaymentMethod(): PaymentMethodEnum
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @throws NegativeQuantityException
     */
    public function addItem(CartItem $item): void
    {
        foreach ($this->items as $existsItem) {
            if ($existsItem->getProductUuid() === $item->getProductUuid()) {
                $existsItem->increaseQuantity($item->getQuantity());
                return;
            }
        }

        $this->items[] = $item;
    }

    /**
     * @throws InvalidCartItemException
     * @throws InvalidUuidException
     */
    private function validate(): void
    {
        if ($this->uuid === "") {
            throw new InvalidUuidException();
        }

        foreach ($this->items as $item) {
            // Проверяем на соответствие типов
            if (!($item instanceof CartItem)) {
                throw new InvalidCartItemException();
            }
        }
    }
}
