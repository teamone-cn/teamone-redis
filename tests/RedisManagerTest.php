<?php

namespace Teamone\RedisTest;

use PHPUnit\Framework\TestCase;
use Teamone\Redis\Connection;
use Teamone\Redis\RedisConnector;
use Teamone\Redis\RedisManager;

class RedisManagerTest extends TestCase
{
    protected function getConfig(): array
    {
        /**
         * Connects to a Redis instance.
         *
         * @param string $host can be a host, or the path to a unix domain socket
         * @param int $port optional
         * @param float $timeout value in seconds (optional, default is 0.0 meaning unlimited)
         * @param string $persistent_id identity for the requested persistent connection
         * @param int $retry_interval retry interval in milliseconds.
         * @param float $read_timeout value in seconds (optional, default is 0 meaning unlimited)
         * @param array $context since PhpRedis >= 5.3.0 can specify authentication and stream information on connect
         */

        $configs = [
            "default" => [
                'driver'         => RedisConnector::class,
                'host'           => 'redis.kitgor.com',
                'port'           => 6379,
                'timeout'        => 0.0,
                'retry_interval' => 1000, // 重试间隔，单位为毫秒。
                'read_timeout'   => 0,
                'username'       => null,
                'password'       => '123456',
                'database'       => 0,
                'prefix'         => 'default:',
                'name'           => 'REDIS',
                'wait_timeout'   => 5, // 连接失败时，等待多久时间重新连接
            ],
            "queue"   => [
                'driver'         => RedisConnector::class,
                'host'           => 'redis.kitgor.com',
                'port'           => 6379,
                'timeout'        => 0.0,
                'retry_interval' => 1000, // 重试间隔，单位为毫秒。
                'read_timeout'   => 0,
                'username'       => null,
                'password'       => '123456',
                'database'       => 1,
                'prefix'         => 'queue:',
                'name'           => 'REDIS',
                'wait_timeout'   => 5, // 连接失败时，等待多久时间重新连接
            ],
        ];
        return $configs;
    }

    protected function getDefaultRedisManager(string $name): Connection
    {
        $redisManager = new RedisManager($this->getConfig());
        return $redisManager->connection($name);
    }

    public function testDefaultConnection(): Connection
    {
        $connection = $this->getDefaultRedisManager("default");

        $this->assertTrue(true);

        return $connection;
    }

    public function testQueueConnection(): Connection
    {
        $connection = $this->getDefaultRedisManager("queue");

        $this->assertTrue(true);
        return $connection;

    }

    public function testDefaultWrite()
    {
        $this->testDefaultConnection()->set("name", "hajar");
        $this->assertTrue(true);
    }

    public function testDefaultRead()
    {
        $value = $this->testDefaultConnection()->get("name");
        $this->assertEquals($value, "hajar");
    }

    public function testQueueWrite()
    {
        $this->testQueueConnection()->set("name", "hajar");
        $this->assertTrue(true);
    }

    public function testQueueRead()
    {
        $value = $this->testQueueConnection()->get("name");
        $this->assertEquals($value, "hajar");
    }
}
