<?php


use PHPUnit\Framework\TestCase;
use Teamone\Redis\RedisConnector;
use Teamone\Redis\RedisManager;

class CustomRedisTest extends TestCase
{
    /**
     * @var Redis
     */
    protected $redis;

    protected function getRedis(): Redis
    {
        return $this->redis;
    }

    // 获取配置
    protected function getConfig(): array
    {
        $configs = [
            "default" => [
                'driver'         => \Teamone\RedisTest\CustomRedisConnector::class,
                'host'           => 'redis.jukit.loc',
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
        ];
        return $configs;
    }

    protected function setUp(): void
    {
        // Redis 实例管理器
        $redisManager = new RedisManager($this->getConfig());
        // 连接
        $this->redis = $redisManager->connection();
    }

    public function testCustomRedisConnector()
    {
        dump('testCustomRedisConnector');

        $result = $this->redis->set('hello', 'teamone');
        dump($result);
        $this->assertTrue($result);

    }

}
