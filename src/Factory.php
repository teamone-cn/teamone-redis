<?php

namespace Teamone\Redis;

interface Factory
{
    /**
     * Get a Redis connection by name.
     *
     * @param  string|null  $name
     * @return Connection
     */
    public function connection($name = null) : Connection;
}
