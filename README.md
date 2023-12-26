# Teamone Redis Client

## 简介

![TeamOne](https://font.thwpmanage.com/img/teamone.jpg) 

Teamone Redis Client 是【霆万平头哥】开发的一个 PHP Redis 客户端连接组件，此组件支付超时重连。

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

$result = $redis->set("name", "hajar");
var_dump($result);

```


