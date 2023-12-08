<?php

namespace Teamone\Redis;

use Redis;
use RedisException;

class RedisConnector implements Connector
{
    /**
     * @desc 创建新的连接
     * @param array $config
     * @return Connection
     */
    public function connect(array $config): Connection
    {
        $connector = function (array $config): Redis {
            return $this->createClient($config);
        };

        $connection = new RedisConnection($connector($config), $connector, $config);

        if (isset($config['name'])) {
            $connection->setName((string)$config['name']);
        }

        return $connection;
    }

    /**
     * @desc 创建实例
     * @param array $config 配置
     * @return Redis
     * @throws RedisException
     */
    protected function createClient(array $config): Redis
    {
        // 创建连接
        $client = $this->createConnect($config);

        if (isset($config['password'])) {
            if (isset($config['username']) && !empty($config['username']) && !empty($config['password'])) {
                $client->auth([$config['username'], $config['password']]);
            } else {
                $client->auth($config['password']);
            }
        }

        $client->select((int)($config['database'] ?? 0));

        if (isset($config['prefix'])) {
            $client->setOption(Redis::OPT_PREFIX, (string)$config['prefix']);
        }
        if (isset($config['scan'])) {
            $client->setOption(Redis::OPT_SCAN, (int)$config['scan']);
        }
        if (isset($config['serializer'])) {
            $client->setOption(Redis::OPT_SERIALIZER, (int)$config['serializer']);
        }
        if (isset($config['compression'])) {
            $client->setOption(Redis::OPT_COMPRESSION, (int)$config['compression']);
        }
        if (isset($config['compression_level'])) {
            $client->setOption(Redis::OPT_COMPRESSION_LEVEL, (int)$config['compression_level']);
        }

        return $client;
    }

    /**
     * @desc 创建连接
     * @param array $config 连接配置
     * @param int $reconnect 重连次数
     * @return Redis
     * @throws RedisException
     */
    protected function createConnect(array $config, int $reconnect = 3): Redis
    {
        try {
            $client = new Redis();

            $client->connect(
                $config['host'] ?? '127.0.0.1',
                $config['port'] ?? 6379,
                $config['timeout'] ?? 0.0,
                null,
                $config['retry_interval'] ?? 0,
                $config['read_timeout'] ?? 0
            );

            return $client;

        } catch (RedisException $e) {
            // 重连次数
            if ($reconnect > 0) {
                $waitTimeout = $config['wait_timeout'] ?? 3;
                sleep($waitTimeout);
                return $this->createConnect($config, $reconnect - 1);
            }

            throw $e;
        }

    }
}
