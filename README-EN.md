# Teamone Redis Client

## Introduction

![TeamOne](https://font.thwpmanage.com/img/teamone.jpg) 

Teamone Redis Client is a PHP Redis client connection component developed by Teamone. It aims to provide developers with a convenient, stable solution for communicating with Redis servers. This component features automatic reconnection, ensuring application stability and reliability even in case of connection issues.

## Key Features

1. Automatic Reconnection: Teamone Redis Client supports automatic reconnection. In case of connection issues, it will automatically reconnect to ensure a stable connection with the Redis server.

2. Flexible Configuration: Easily specify connection parameters for the Redis server through simple configuration, including host address, port, timeout duration, retry interval, username, password, database, and connection prefix.

3. Multi-instance Support: Supports configuration of multiple Redis instances for flexible switching according to different business scenarios.

4. Simplicity and Ease of Use: Provides a simple and easy-to-use API, allowing developers to perform Redis data operations without worrying about underlying connection details.

5. Error Handling: Comprehensive error handling mechanism ensures timely capture and handling of connection and operation exceptions, guaranteeing application stability.

6. Integration with WordPress: Seamlessly integrate Redis with WordPress as a caching storage backend to enhance the performance and response speed of WordPress websites. Easily configure Redis integration with WordPress to achieve efficient data caching and fast access.

## Project Goals

Teamone Redis Client aims to provide a stable, efficient solution for PHP developers to communicate with Redis servers. We will continue to improve and enhance the component's functionality, providing users with excellent development experience and high performance. We welcome contributions and feedback from the community to drive the project's development and progress.

## Installation

```shell
composer require teamone/redis
```

## Getting Started

### 1. Configuration

```php
$configs = [
    // Configuration for default connection
    "default" => [
        'driver'         => RedisConnector::class,
        'host'           => '127.0.0.1',
        'port'           => 6379,
        'timeout'        => 0.0,
        'retry_interval' => 1000,
        'read_timeout'   => 0,
        'username'       => null,
        'password'       => '123456',
        'database'       => 0,
        'prefix'         => 'default:',
        'name'           => 'Redis',
        'wait_timeout'   => 5,
    ],
];
```

### 2. Initialization and Usage

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
        'retry_interval' => 1000,
        'read_timeout'   => 0,
        'username'       => null,
        'password'       => '123456',
        'database'       => 0,
        'prefix'         => 'default:',
        'name'           => 'Redis',
        'wait_timeout'   => 5,
    ],
    "queue"   => [
        'driver'         => RedisConnector::class,
        'host'           => '127.0.0.1',
        'port'           => 6379,
        'timeout'        => 3.0,
        'retry_interval' => 1000,
        'read_timeout'   => 0,
        'username'       => null,
        'password'       => '123456',
        'database'       => 1,
        'prefix'         => 'queue:',
        'name'           => 'Redis',
        'wait_timeout'   => 5,
    ],
];

$name = "default"; // or queue
$manager = new RedisManager($configs);
/** @var Redis $redis */
$redis = $manager->connection($name);

$result = $redis->set("name", "Teamone");
var_dump($result);

```

### Running Unit Tests

````shell
./vendor/bin/phpunit ./test/RedisTest.php --filter testConnect
````


