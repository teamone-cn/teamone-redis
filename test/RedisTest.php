<?php

namespace Teamone\RedisTest;

class RedisTest extends RedisInit
{
    public function testConnect(): void
    {
        $result = $this->redis->ping('pong');
        dump($result);

        $this->assertEquals($result, 'pong');
    }

    public function testWrite(): void
    {
        $key    = 'hello';
        $value  = 'world';
        $result = $this->redis->set($key, $value);
        dump($result);

        $this->assertTrue($result);
    }

    public function testRead(): void
    {
        $key   = 'hello';
        $value = 'world';

        $this->redis->set($key, $value);
        $result = $this->redis->get($key);
        dump($result);
        $this->redis->expire($key, 20);

        $this->assertEquals($result, $value);
    }


    public function testConnectorLoop()
    {

        $this->redis->set("name", "Teamone");

        $i = 0;
        while (true) {
            if ($i >= 50) {
                break;
            }

            $value = $this->redis->get("name");

            dump("{$i} -> {$value}");

            $i++;
            sleep(1);
        }

        $this->assertEquals($this->redis->ping("pong"), "pong");

    }
}
