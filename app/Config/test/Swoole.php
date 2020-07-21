<?php
/**
 * Swoole模式
 * Server配置
 */
return array(
    //主服务，选主服务 建议按 websocket（http） > http > udp || tcp 顺序创建 ,websocket只能作为主进程
    "server" => array(
        "server_name" => "fend",
        "host"        => "0.0.0.0",
        "port"        => 9572,
        "class"       => "swoole_http_server",//可选项：swoole_websocket_server/swoole_http_server/swoole_server
        "socket"      => SWOOLE_SOCK_TCP,
        'classname'   => '\Fend\Server\Dispatcher\Http',
    ),

    "listen" => array(/*
        "httpserver" => array(
            "host"       => "0.0.0.0",
            "port"       => 9572,
            "socket"     => SWOOLE_SOCK_TCP,
            'classname'  => '\Fend\Dispatcher\Http',
            "protocol"   => array(
                'open_http_protocol' => 1,
                'open_tcp_nodelay'   => 1,
            ),
        )
        */
    ),

    //共享table
    "table" => array(
        "share" => array(
            "column"      => array(
                array("key" => "time", "type" => "int", "len" => 4),
                array("key" => "data", "type" => "string", "len" => 1024),
            ),
            "size"       => 1024, //最大保存多少条，建议超过实际个数
            "proportion" => 0.2, //hash预留空间比例 https://wiki.swoole.com/wiki/page/254.html
            "dumpfile" => __DIR__ . "/../share.dump", //落地磁盘路径，设置会flash到磁盘，重启会加载
        ),
    ),

    "swoole" => array(
        //'user' => 'www',
    //'group' => 'www',
    'dispatch_mode'      => 1,
    'package_max_length' => 2097152, // 1024 * 1024 * 2,
    'buffer_output_size' => 3145728, //1024 * 1024 * 3,
    'pipe_buffer_size'   => 33554432, //1024 * 1024 * 32,

    'backlog'                  => 30000,
    'open_tcp_nodelay'         => 1,
    'heartbeat_idle_time'      => 180,
    'heartbeat_check_interval' => 60,

    'open_cpu_affinity'       => 1,
    'worker_num'              => 10, //注意测试使用这个配置，线上开大
    //'task_worker_num'   => 1,
    'max_request'             => 0, //注意测试使用这个配置，线上开大
    //'task_max_request'  => 1,
    'discard_timeout_request' => false,
    'log_level'               => 2, //swoole 日志级别 Info
    'log_file'                => '/tmp/baseserver.log',//swoole 系统日志，任何代码内echo都会在这里输出
    'task_tmpdir'             => '/tmp/baseserver/',//task 投递内容过长时，会临时保存在这里，请将tmp设置使用内存
    'pid_file'                => '/tmp/baseserver.pid',//进程pid保存文件路径，请写绝对路径

    'request_slowlog_timeout' => 3,
    'request_slowlog_file'    => '/tmp/base_slow.log',
    'trace_event_worker'      => true,

    'reload_async'            => true,
    'max_wait_time'           => 2, //重启最大等待时间
    'daemonize' => 0,//注意测试使用配置0，线上建议1
    'enable_coroutine' => true,
),


);
