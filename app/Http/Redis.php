<?php

namespace App\Http;

use Fend\Cache;

class Redis
{

    public function setString()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            for ($i = 0; $i < 100; $i++) {
                $redis->set("string-$i", mt_rand(1, 10000));
                $result[$i] = $redis->get("string-$i");
            }
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }

    }

    public function getString()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            for ($i = 0; $i < 100; $i++) {
                $result[$i] = $redis->get("string-$i");
            }
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }

    }

    public function lpush()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->lpush("list-test", mt_rand(1, 10000));
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }

    }

    public function lpop()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->lPop("list-test");
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }

    }

    public function zadd()
    {
        try {
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            mt_srand(microtime(true) * mt_rand(123,12332));
            $score =  mt_rand(1, 1000);
            mt_srand(microtime(true) * mt_rand(2,1133));
            $key = 'ok' . mt_rand(1, 10100);
            $result = $redis->zAdd("sortset-test",$score, $key);
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function zrange()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->zRange("sortset-test", 0, -1, true);
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function zdel()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->del("sortset-test");
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function hset()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->hset("hset-test", "key" . mt_rand(1, 2000), mt_rand(1, 2000));
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function hgetall()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->hGetAll("hset-test");
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function hdel()
    {
        try {
            $result = [];
            $redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
            $result = $redis->del("hset-test");
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }
}