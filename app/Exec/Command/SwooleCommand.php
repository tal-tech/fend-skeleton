<?php


namespace App\Exec\Command;

use Fend\Console\BaseCommand;
use Fend\Server\BaseServer;
use Swoole\Process;

class SwooleCommand extends BaseCommand
{

    public $signature = 'swoole';
    public $desc = 'swoole service option(start, reload, restart, kill)';
    public $params = [
        ["operate", "required", "swoole services"],
    ];

    public $optional = [
        ["config", "c", "required", "swoole config file path"],
        ["debug", "d", "optional", "swoole start mode(d or debug to debug mode)"],
        ["pid", "p", "optional", "swoole pid file"],
    ];

    private $extensionList = [
        "pcntl",
        "mysqli",
        "memcached",
        "pdo_mysql",
        "mbstring",
        "json",
        "curl",
        "bcmath",
        "redis",
        "swoole",
    ];

    private $swooleConfig = [];

    /**
     * swoole 操作
     *
     * @param $param array
     * @throws \Exception
     */
    public function handle(array $param)
    {
        //检查扩展
        $this->check();

        //配置
        $this->config($param["config"]);

        //配置启动模式
        if(isset($param["debug"])){
            $this->debugMode();
        }

        //配置pid文件
        if(isset($param["pid"])) {
            $this->setPid($param["pid"]);
        }

        //操作
        $this->operation($param["operate"]);
    }

    /**
     * swoole  操作
     * @param $operation string
     * @throws \Exception
     */
    private function operation($operation)
    {
        switch ($operation) {
            case 'start':
            {
                // 开启进程
                echo 'start:' . $this->swooleConfig['server']['server_name'] . PHP_EOL;
                $server = new BaseServer($this->swooleConfig);
                $server->start();
                break;
            }
            case 'stop':
            {
                // 给 master 进程发送信号量 15
                echo 'stop:' . $this->swooleConfig['server']['server_name'] . PHP_EOL;
                $pid = file_get_contents($this->swooleConfig['swoole']['pid_file']);
                if ($pid > 0) {
                    $ret = Process::kill($pid);
                    echo "kill the server pid: " . $pid . " ret: " . $ret . PHP_EOL;
                } else {
                    echo "pid file not found" . PHP_EOL;
                }
                break;
            }
            case 'reload':
            {
                // SIGUSR1
                $pid = file_get_contents($this->swooleConfig['swoole']['pid_file']);
                echo 'reload:' . $this->swooleConfig['server']['server_name'] . " pid:" . $pid . PHP_EOL;

                if ($pid > 0) {
                    $cmd = "kill -s 10 $pid";
                    $ret = exec($cmd, $outStr);
                    echo "send signal to pid:" . $pid . " ret:" . $ret . " output:" . PHP_EOL;
                    var_dump($outStr);
                } else {
                    echo "pid file not found" . PHP_EOL;
                }

                break;
            }
            case 'restart':
            {
                // stop、start
                $pid = file_get_contents($this->swooleConfig['swoole']['pid_file']);

                echo 'stop:' . $this->swooleConfig['server']['server_name'] . " pid:" . $pid . PHP_EOL;
                if ($pid > 0) {
                    $cmd = "kill  $pid";
                    exec($cmd, $outStr);
                }

                sleep(3);

                //reload pid
                echo 'start:' . $this->swooleConfig['server']['server_name'] . PHP_EOL;
                $server = new BaseServer($this->swooleConfig);
                $server->start();

                break;
            }
            case 'kill':
            {
                //暴力kill
                $name = $this->swooleConfig['server']['server_name'];
                echo 'kill:' . $name . PHP_EOL;
                $cmd = "ps -ef | grep $name | grep -v grep | cut -c 9-15 | xargs kill -s 9 ";
                exec($cmd, $outStr);
                break;
            }
            default:
            {
                $this->help();
                exit;
            }
        }
    }

    /**
     * 校验必须元素
     */
    private function check()
    {
        $this->checkExt();
        $this->checkSwoole();
    }

    /**
     * 检查swoole版本
     */
    private function checkSwoole()
    {
        //check version of swoole
        if (swoole_version() < 1.10) {
            die('swoole extension version is wrong. you must run this more than 1.10.x version' . PHP_EOL);
        }
    }

    /**
     * 校验扩展
     */
    private function checkExt()
    {
        foreach ($this->extensionList as $extenName) {
            if (!extension_loaded($extenName)) {
                die ($extenName . ' extension must install' . PHP_EOL);
            }
        }
    }

    /**
     * 设置启动的模式
     */
    private function debugMode()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL);
        //no daemonize
        $this->swooleConfig["swoole"]["daemonize"] = 0;
        //base server
        $this->swooleConfig["server"]["process_mode"] = SWOOLE_BASE;
        //one worker
        $this->swooleConfig["swoole"]["worker_num"] = 1;
        //max_request
        $this->swooleConfig["swoole"]["max_request"] = 2;

        echo "opened the debug info for console.." . PHP_EOL;
    }

    /**
     * @param $pidFile string pid文件路径
     */
    private function setPid($pidFile)
    {
        if ($pidFile) {
            $this->swooleConfig["swoole"]["pid_file"] = $pidFile;
        }
    }

    /**
     * 加载配置文件
     *
     * @param $configFile string 配置文件路径
     */
    private function config($configFile)
    {
        if (!file_exists($configFile)) {
            die("config file not found.." . PHP_EOL);
        }

        $this->swooleConfig = include "$configFile";
    }

    /**
     * 帮助文档
     */
    public function help()
    {
        $helpDom = "=======================================" . PHP_EOL;
        $helpDom .= "==   Start Controller Console   ==" . PHP_EOL;
        $helpDom .= "=======================================" . PHP_EOL;
        $helpDom .= "" . PHP_EOL;

        $helpDom .= "CMD: php Bin/fend swoole -c app/Config/Swoole.php start " . PHP_EOL;
        $helpDom .= "OR:  php Bin/fend swoole -c app/Config/Swoole.php -d 1 start " . PHP_EOL;

        $helpDom .= "\t-c\t\tthis is ConfigFile Path" . PHP_EOL;

        $helpDom .= "\toperate\t\t see below CMD" . PHP_EOL;

        $helpDom .= "\t-d \t\twhen it's set to be d or debug " . PHP_EOL;

        $helpDom .= "\t-p\t\tpid file path" . PHP_EOL;

        $helpDom .= "CMD:" . PHP_EOL;

        $helpDom .= "\tstart\t\tstart server" . PHP_EOL;

        $helpDom .= "\tstop\t\tstop server" . PHP_EOL;

        $helpDom .= "\tkill\t\tkill all server process" . PHP_EOL;

        $helpDom .= "\trestart\t\trestart server" . PHP_EOL;

        $helpDom .= "\treload\t\treload file for worker" . PHP_EOL;

        $helpDom .= "---------------------------------------" . PHP_EOL;

        echo $helpDom;
    }
}