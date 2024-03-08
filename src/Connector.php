<?php

namespace Teamone\Redis;

interface Connector
{
    /**
     * 创建一个连接到 Redis 实例
     *
     * @param  array  $config
     * @return Connection
     */
    public function connect(array $config) : Connection;
}
