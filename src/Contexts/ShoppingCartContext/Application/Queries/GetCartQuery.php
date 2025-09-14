<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries;

readonly class GetCartQuery
{
    public function __construct(
        public string $cartUuid
    )
    {
        if ($this->cartUuid === "") {
            throw new \InvalidArgumentException('Uuid корзины не должен быть пустым');
        }
    }
}