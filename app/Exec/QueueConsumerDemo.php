<?php

use Fend\Process\QueueConsumer;

require_once(dirname(dirname(__FILE__)) . '/../init.php');

class QueueProcess Extends QueueConsumer
{

    protected $config = "kafka_agent";

    /**
     * 收到消息后具体处理封装
     * @param array $task 任务信息
     * @throws \Fend\Queue\Exception
     */
    public function handle($task)
    {
        /*
        $topic = $task["topic"]; //kafka topic
        $key = $task["key"]; //分区所需的计算因子，同一个key会分配同一个kafka分区，保证顺序
        $partition = $task["partition"]; //分区号
        $offset = $task["offset"]; //消费数据的offset
        */

        //存在就代表此任务被获取后超时，任务重新投递
        $repeat = $task["headers"]["map"]["mqproxy-redelivery"] ?? null;
        //任务信息
        $taskData = json_decode($task["value"], true);

        $this->finished($task);
    }
}

$server = new QueueProcess();
$server->start();