<?php

namespace Teamone\Redis;

use InvalidArgumentException;
use ReflectionClass;

class RedisManager implements Factory
{
    /**
     * @var Connection[]
     */
    private $connections;

    /**
     * @var array
     */
    private $configs;

    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * @desc 根据名称获取连接
     * @param $name
     * @return Connection
     */
    public function connection($name = null): Connection
    {
        $name = $name ?: 'default';

        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }

        return $this->connections[$name] = $this->resolve($name);
    }

    /**
     * @desc 解析连接
     * @param string $name
     * @return Connection
     */
    protected function resolve(string $name = 'default'): Connection
    {
        // 获取配置
        $config = $this->configure($name);
        // 解析连接
        return $this->connector($config['driver'] ?? "")->connect($config);
    }

    /**
     * @desc 获取配置
     * @param string $name
     * @return array
     */
    protected function configure(string $name): array
    {
        if (!isset($this->configs[$name])) {
            throw new InvalidArgumentException("Redis connection [{$name}] not configured.");
        }

        return (array)$this->configs[$name];
    }

    /**
     * @desc 根据驱动解析连接器
     * @param string $driver
     * @return Connector
     * @throws \ReflectionException
     */
    protected function connector(string $driver): Connector
    {
        //
        if (class_exists($driver)) {
            $reflection = new ReflectionClass($driver);
            $instance   = $reflection->newInstance();
            if ($instance instanceof Connector) {
                return $instance;
            }
        }

        return new RedisConnector();
    }
}
