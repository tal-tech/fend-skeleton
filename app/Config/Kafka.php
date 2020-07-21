<?php

return [
    "default" => [
        "Broker" => "192.168.1.1:9092,192.168.1.2:9092,192.168.1.3:9092",
        "Group" => "cltest", //消费分组
        "Queue" => 'hehehe', //topic
        "ConsumerTimeout" => 1000, //消费时等待消息时间，超时没有返回内容会返回false，单位ms
        "AutoCommit" => -1, //、是否自动提交消费offset，如果设置为-1为手动提交、设置为>0为自动提交单位ms、默认自动提交
    ]
];