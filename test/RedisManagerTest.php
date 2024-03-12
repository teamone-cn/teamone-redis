<?php

namespace Teamone\RedisTest;


class RedisManagerTest extends RedisInit
{
    public function testDefaultWrite()
    {
        $result = $this->redis->set("name", "Teamone");
        dump($result);

        $this->assertTrue($result);
    }

    public function testDefaultRead()
    {
        $result = $this->redis->get("name");
        dump($result);
        $this->assertEquals($result, "Teamone");
    }

    public function testQueueWrite()
    {
        $this->changeRedisManager("queue");

        $result = $this->redis->set("name", "Teamone");
        dump($result);

        $this->assertTrue($result);
    }

    public function testQueueRead()
    {
        $this->changeRedisManager("queue");

        $result = $this->redis->get("name");
        dump($result);

        $this->assertEquals($result, "Teamone");
    }
}
