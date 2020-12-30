<?php

declare(strict_types=1);

namespace App\Service;

use Redis;

class RedisService
{
    /**
     * @var Redis
     */
    private $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function set(string $key, $value, int $time = 3600): void
    {
        $this->redis->set($key, $value);
        $this->redis->expire($key, $time);
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function del(string $key)
    {
        $this->redis->del($key);
    }
}