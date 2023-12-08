<?php

namespace Teamone\Redis;

use Redis;

abstract class Connection
{
    /**
     * @var Redis 客服端
     */
    protected $client;

    /**
     * @var string Redis 连接名称
     */
    private $name;

    public function client()
    {
        return $this->client;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function command(string $method, array $parameters = [])
    {
        $start = microtime(true);

        $result = $this->client()->{$method}(...$parameters);

        $end = microtime(true);

        $time = round(($end - $start) * 1000, 2);

        return $result;
    }

    public function __call(string $method, array $parameters)
    {
        return $this->command($method, $parameters);
    }
}
