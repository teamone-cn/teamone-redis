# Teamone Redis Client

## 简介

![TeamOne](https://font.thwpmanage.com/img/teamone.jpg) 

Teamone Redis Client 是【霆万平头哥】开发的一个 PHP Redis 客户端连接组件，旨在为开发者提供方便、稳定的与 Redis 服务器进行通信的解决方案。该组件具备超时重连功能，能够确保在连接异常时能够自动进行重连操作，保障应用的稳定性和可靠性。

## 主要特性

1. 超时重连：Teamone Redis Client 支持超时重连功能，当连接异常时，会自动进行重连操作，确保与 Redis 服务器的稳定连接。

2. 灵活配置：通过简单的配置，可以轻松指定 Redis 服务器的连接参数，包括主机地址、端口、超时时间、重试间隔、用户名、密码、数据库、连接前缀等。

3. 多实例支持：支持配置多个 Redis 实例，可以根据实际需求进行灵活切换，满足不同业务场景的需求。

4. 简洁易用：提供简洁易用的 API，开发者无需关心底层连接细节，即可方便地进行 Redis 数据操作。

5. 错误处理：具备完善的错误处理机制，能够及时捕获并处理连接和操作过程中的异常情况，保障应用的稳定性。

6. 可以与 WordPress 结合使用，通过将 Redis 作为 WordPress 的缓存存储后端，有效地提升 WordPress 网站的性能和响应速度。通过简单的配置，可以轻松将 Redis 与 WordPress 集成，实现数据的高效缓存和快速访问。

## 项目目标

Teamone Redis Client 旨在为 PHP 开发者提供一个稳定、高效的与 Redis 服务器通信的解决方案。我们将持续改进和完善组件功能，为用户提供更加优秀的开发体验和更高的性能表现。同时，我们欢迎社区的贡献和反馈，共同推动项目的发展和进步。

## 安装

```shell
composer require teamone/redis
```

## 接入指南

### 一、配置说明

```php
$configs = [
    // 配置名称: default
    "default" => [
        // 连接驱动，可以自行重写此实现类
        'driver'         => RedisConnector::class,
        // 地址
        'host'           => '127.0.0.1',
        // 端口
        'port'           => 6379,
        // 连接超时时间，0.0 表示不限时
        'timeout'        => 0.0,
         // 重试间隔，单位为毫秒。
        'retry_interval' => 1000,
        // 读取超时时间，0 表示不限时
        'read_timeout'   => 0,
        // 用户名
        'username'       => null,
        // 密码
        'password'       => '123456',
        // 数据库 0~15
        'database'       => 0,
        // 键前缀
        'prefix'         => 'default:',
        // 实例的名称（内部使用，默认 Redis 即可）
        'name'           => 'Redis',
        // 连接失败时，等待多久时间重新连接
        'wait_timeout'   => 5,
    ],
];
```

### 二、初始化并使用

```php

use Teamone\Redis\RedisConnector;
use Teamone\Redis\RedisManager;
use Redis;

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

$result = $redis->set("name", "Teamone");
var_dump($result);

```

### 三、执行单元测试

````shell
./vendor/bin/phpunit ./test/RedisTest.php --filter testConnect
````


