<?php

namespace App\Http;

use Fend\Queue;
use Fend\Config;

class Kafka
{

    public function produce()
    {
        try {
            $queue = Queue::factory(Queue::QUEUE_TYPE_RDKAFKA, "default");
            $result = $queue->pushQueue("test msg:" . mt_rand(1, 100000));
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function consumer()
    {
        try {
            $queue = Queue::factory(Queue::QUEUE_TYPE_RDKAFKA, "default");
            $result = $queue->consumer();
            if ($result) {
                $queue->commitAck($result);
            }
            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }
}