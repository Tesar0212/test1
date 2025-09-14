<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Uuid;

use Ramsey\Uuid\Uuid;

class UuidGenerator implements UuidGeneratorInterface
{

    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}