<?php

namespace Teamone\Redis;

interface Factory
{
    /**
     * 获取 Redis 连接
     *
     * @param  string|null  $name
     * @return Connection
     */
    public function connection($name = null) : Connection;
}
