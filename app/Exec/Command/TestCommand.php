<?php


namespace App\Exec\Command;


use Fend\Console\BaseCommand;

class TestCommand extends BaseCommand
{
    public $signature = "test";
    public $desc = "测试";

    protected $params = [
        ["operate", "required", "计算操作"],
        ["params", 'is_array', "计算参数"],
    ];

    protected $optional = [
        ["debug", "d", 'none', "debug模式"],
    ];

    public function handle(array $params)
    {
        //配置启动模式
        if(isset($params["debug"]) || $params["debug"]){
            return 'debug';
        }

        switch ($params['operate']) {
            case 1: {
                $p = $params['params'];
                echo($p[0] + $p[1]);
                return $p[0] + $p[1];
            }
            case 2: {
                $p = $params['params'];
                echo($p[0] - $p[1]);
                return $p[0] - $p[1];
            }
            default: {
                throw new \Exception('Invalid Operate');
            }
        }
    }
}