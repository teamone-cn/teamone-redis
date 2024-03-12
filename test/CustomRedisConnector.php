<?php
namespace Teamone\RedisTest;

use Teamone\Redis\Connector;
use Redis;
use Teamone\Redis\RedisConnection;
use Teamone\Redis\Connection;
use RedisException;

class CustomRedisConnector implements Connector
{
    /**
     * @desc 创建新的连接
     *
     * $config = [
     * // 连接驱动，可以自行重写此实现类
     * 'driver'         => RedisConnector::class,
     * // 地址
     * 'host'           => '127.0.0.1',
     * // 端口
     * 'port'           => 6379,
     * // 连接超时时间，0.0 表示不限时
     * 'timeout'        => 0.0,
     * // 重试间隔，单位为毫秒。
     * 'retry_interval' => 1000,
     * // 读取超时时间，0 表示不限时
     * 'read_timeout'   => 0,
     * // 用户名
     * 'username'       => null,
     * // 密码
     * 'password'       => '123456',
     * // 数据库 0~15
     * 'database'       => 0,
     * // 键前缀
     * 'prefix'         => 'default:',
     * // 实例的名称（内部使用，默认 Redis 即可）
     * 'name'           => 'Redis',
     * // 连接失败时，等待多久时间重新连接
     * 'wait_timeout'   => 5,
     * ];
     *
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
    final protected function createConnect(array $config, int $reconnect = 3): Redis
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
            if ($reconnect > 0) {
                $waitTimeout = $config['wait_timeout'] ?? 3;
                sleep($waitTimeout);
                return $this->createConnect($config, $reconnect - 1);
            }

            throw $e;
        }
    }
}
