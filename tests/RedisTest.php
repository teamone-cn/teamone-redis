<?php

namespace Teamone\RedisTest;

use PHPUnit\Framework\TestCase;
use Redis;
use Teamone\Redis\Connection;
use Teamone\Redis\RedisConnector;

class RedisTest extends TestCase
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

        $inputConfig = [
            'driver'         => RedisConnector::class,
            'host'           => 'redis.kitgor.com',
            'port'           => 6379,
            'timeout'        => 0.0,
            'retry_interval' => 1000, // 重试间隔，单位为毫秒。
            'read_timeout'   => 0,
            'username'       => null,
            'password'       => '123456',
            'database'       => 0,
            'prefix'         => 'teamone:',
            'name'           => 'REDIS',
            'wait_timeout'   => 5, // 连接失败时，等待多久时间重新连接
        ];
        return $inputConfig;
    }

    protected function getRedis(): Redis
    {
        $redisConnector = new RedisConnector();
        return $redisConnector->connect($this->getConfig())->client();
    }

    public function testConnect(): void
    {
        $redis = $this->getRedis();
        $this->assertTrue($redis->ping(), "ok");
    }

    public function testWrite(): void
    {
        $redis = $this->getRedis();
        $redis->set("hello", "world");
        $redis->expire("hello", 20);

        $this->assertTrue($redis->ping(), "ok");
    }

    public function testRead(): void
    {
        $redis = $this->getRedis();
        $redis->set("hello", "world");
        $value = $redis->get("hello");
        $redis->expire("hello", 20);

        $this->assertEquals($value, "world");
    }

    protected function getRedisConnector(): Connection
    {
        $redisConnector = new RedisConnector();
        return $redisConnector->connect($this->getConfig());
    }

    public function testConnectorWrite()
    {
        $connection = $this->getRedisConnector();

        $connection->set("name", "杰哥 jukit:" . date('Y-m-d H:i:s'));
        $connection->expire("name", 30);

        $this->assertEquals($connection->ping("ping"), "ping");

    }

    public function testConnectorRead()
    {
        $connection = $this->getRedisConnector();
        $value      = $connection->get("name");

        $this->assertTrue(true);

    }

    public function testConnectorLoop()
    {
        $connection = $this->getRedisConnector();

        $connection->set("name", "jingjing");

        $i = 0;
        while (true) {
            if ($i >= 50) {
                break;
            }

            $value = $connection->get("name");
            $i++;
            sleep(1);
        }

        $this->assertEquals($connection->ping("ping"), "ping");

    }
}
