<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): string;
}