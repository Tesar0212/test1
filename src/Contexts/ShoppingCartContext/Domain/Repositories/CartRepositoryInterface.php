<?php

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Aggregates\Cart;

interface CartRepositoryInterface
{
    public function find(string $uuid): Cart;
    public function save(Cart $cart): void;
}