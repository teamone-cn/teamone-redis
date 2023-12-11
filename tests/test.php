<?php

require __DIR__ . '/../vendor/autoload.php';

use Teamone\Redis\RedisConnector;
use Teamone\Redis\RedisManager;

$configs = [
    "default" => [
        'driver'         => RedisConnector::class,
        'host'           => '127.0.0.1',
        'port'           => 6379,
        'timeout'        => 3.0,
        'retry_interval' => 1000, // 重试间隔，单位为毫秒。
        'read_timeout'   => 0,
        'username'       => null,
        'password'       => '123456',
        'database'       => 0,
        'prefix'         => 'default:',
        'name'           => 'Redis',
        'wait_timeout'   => 5, // 连接失败时，等待多久时间重新连接
    ],
    "queue"   => [
        'driver'         => RedisConnector::class,
        'host'           => '127.0.0.1',
        'port'           => 6379,
        'timeout'        => 3.0,
        'retry_interval' => 1000, // 重试间隔，单位为毫秒。
        'read_timeout'   => 0,
        'username'       => null,
        'password'       => '123456',
        'database'       => 1,
        'prefix'         => 'queue:',
        'name'           => 'Redis',
        'wait_timeout'   => 5, // 连接失败时，等待多久时间重新连接
    ],
];

$name = "default"; // or queue
$manager = new RedisManager($configs);
/** @var Redis $redis */
$redis = $manager->connection($name);
$result = $redis->set("name", "hajar");
var_dump($result);
/*输出
bool(true)
*/
