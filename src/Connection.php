<?php

namespace Teamone\Redis;

use Redis;
use RedisException;

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

    /**
     * @desc
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws RedisException
     */
    public function command(string $method, array $parameters = [])
    {
        return $this->client()->{$method}(...$parameters);
    }

    public function __call(string $method, array $parameters)
    {
        return $this->command($method, $parameters);
    }
}
