<?php

namespace Teamone\Redis;

use Redis;
use RedisException;

class RedisConnection extends Connection
{
    /**
     * @var callable 连接回调
     */
    protected $connector;

    /**
     * @var array 连接配置
     */
    protected $config;

    public function __construct(Redis $client, callable $connector, array $config)
    {
        $this->client    = $client;
        $this->connector = $connector;
        $this->config    = $config;
    }

    /**
     * @desc 关闭连接
     * @throws RedisException
     */
    public function disconnect()
    {
        try {
            $this->client->close();
        } catch (RedisException $e) {
            $this->client = null;
        }
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
        try {
            return parent::command($method, $parameters);
        } catch (RedisException $e) {

            // 关闭连接
            $this->disconnect();

            $errors = ['went away', 'socket', 'read error on connection', 'Connection lost', 'Connection refused'];

            foreach ($errors as $errorMessage) {
                if (strpos($e->getMessage(), $errorMessage) !== false) {
                    // 重连成功
                    if ($this->reconnections()) {
                        return $this->command($method, $parameters);
                    }
                    break;
                }
            }

            throw $e;
        }
    }

    // 重新连接
    protected function reconnections(): bool
    {
        try {
            $this->client = call_user_func($this->connector, $this->config);
            return true;
        } catch (RedisException $e) {
            return false;
        }
    }

    public function __call(string $method, array $parameters)
    {
        return parent::__call($method, $parameters);
    }
}
