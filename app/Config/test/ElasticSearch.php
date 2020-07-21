<?php
return [
    //默认es配置
    "default" => [
        "hosts" => [
            "127.0.0.1:9200",
        ]
    ],
    //其他es配置，支持密码验证等
    "fend_demo" => [
        "hosts" => [
            [
                'host' => 'foo.com',
                'port' => '9200',
                'scheme' => 'https',
                'path' => '/elastic',
                'user' => 'username',
                'pass' => 'password!#$?*abc'
            ],
            [
                'host' => 'localhost',    // Only host is required
            ]
        ]
    ],
];