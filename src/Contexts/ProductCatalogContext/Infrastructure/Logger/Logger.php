<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ProductCatalogContext\Infrastructure\Logger;

class Logger implements LoggerInterface
{

    // Тут нужно написать реализацию, можно от симфоневского, ларовского лога
    // или свою реализацию придумать

    public function log($message, array $context = [])
    {
        // TODO: Implement log() method.
    }

    public function error($message, array $context = [])
    {
        // TODO: Implement error() method.
    }
}