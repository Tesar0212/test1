<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Redis;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Exceptions\ConnectorException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Connectors\Interfaces\ConnectorInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Redis;
use RedisException;

class Connector implements ConnectorInterface
{
    private Redis $redis;

    public function __construct($redis)
    {
        return $this->redis = $redis;
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key): array
    {
        try {
            return unserialize($this->redis->get($key));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, array $value): void
    {
        try {
            $this->redis->setex($key, 24 * 60 * 60, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    public function has($key): bool
    {
        return $this->redis->exists($key);
    }
}
