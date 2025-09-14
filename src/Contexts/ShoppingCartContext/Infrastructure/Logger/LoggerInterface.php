<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Logger;

interface LoggerInterface
{
    public function log($message, array $context = []);
    public function error($message, array $context = []);

    // и т.п.
}