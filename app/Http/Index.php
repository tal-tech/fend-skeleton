<?php

namespace App\Http;

use App\Model\DemoDBNCModel;
use Fend\Di;
use Fend\Funcs\FendHttp;
use Fend\Read;
use Fend\Request;
use Fend\Response;
use Fend\Template;
use Fend\Write;

/**
 * Class Index
 * 首页
 * @author gary
 */
class Index extends Template
{

    public function Init(Request $request, Response $response)
    {
        $request->setQueryString("yes", 1);
        $this->initTemplate();
    }
	
	public function echo()
	{
		return "123";
	}

    /**
     * 演示网关域名收敛功能，获取当前请求URI 路径
     */
    private function getUrlPrefixPath(Request $request)
    {
        if ($request->header("XES-DOMAIN") !== '' && $request->server("HTTP_HOST", null) !== null) {
            preg_match("#(.*?)\.#i", $request->server("HTTP_HOST"), $match);
            if (isset($match[1])) {
                $subDomain = $match[1];
                return "/" . $subDomain;
            }
        }
        return "";
    }

    public function index(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("index");
    }

    public function Redis(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("redis");
    }

    public function Mysql(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("mysql");
    }

    public function Kafka(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("kafka");
    }

    public function RabbitMQ(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("rabbitmq");
    }

    public function Elastic(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("elastic");
    }

    public function filesystem(Request $request, Response $response)
    {
        $this->assign("baseDir", $this->getUrlPrefixPath($request));
        return $this->showView("filesystem");
    }

    public function cpu()
    {
        for ($i = 0; $i < 1000000; $i++) {
            $result = [];
            $result[$i] = mt_rand(1, 11111111);
            ksort($result, 1);
        }

        return json_encode(["code" => 1, "msg" => "ok", "data" => []], JSON_PRETTY_PRINT);
    }

    public function Config(Request $request, Response $response)
    {
        if ($request->get("tal") === "1") {
            $configList = [];
            $fileList = glob(SYS_ROOTDIR . "app/Config/*.php");
            foreach ($fileList as $configPath) {
                $configName = basename($configPath);
                if (in_array($configName,
                    ["Console.php", "Memcache.php", "Queue.php", "RedisSdk.php", "Swoole.php", "Router.php"])) {
                    continue;
                }
                $configList[$configName] = file_get_contents($configPath);
            }
            $this->assign("baseDir", $this->getUrlPrefixPath($request));

            $this->assign("configList", $configList);
            return $this->showView("config");
        }
    }

    public function Curl(Request $request, Response $response)
    {
        $res = FendHttp::httpRequest('http://127.1.0.1:8080/', [], "get", 3000, [],
            ["connect_timeout" => 111, "retry" => 3]);
        var_dump($res);
        return "";
    }

    /**
     * 首页演示
     * 访问地址： http://www.fend1.com/index/index1?wxdebug=1
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws \Exception
     */
    public function index1(Request $request, Response $response)
    {
        $table = Di::factory()->getTable("share");
        if ($table) {
            $table->set("11", ["time" => time(), "data" => "123"]);
            var_dump($table->get("11"));
        }
        var_dump($request->get());

        //获取$_GET参数
        $user_id = $request->get("user_id");

        //echo输出内容只会在wxdebug=1时显示在控制台，而不会输出给用户
        echo "this is debug output test";

        //写入一条日志
        //Logger::info("this is log test $user_id");

        //如果index没有指定request参数和response参数可以通过这个方式获取
        $response = Di::factory()->getResponse();

        //返回结果是json的话可以使用这个，会顺便帮忙设置header头
        //json如果有特殊参数，可以跟随在这个后面
        $data = $response->json([microtime(true)]);

        //返回一个header头，大小写自理
        $response->header("xxx", "xxx");

        //throw new \Exception("test",1231);

        //返回内容直接以字符串形式return
        return $data;
    }

    /**
     * 500错误演示
     * 如果请求querystring带wxdebug=1会看到更多信息
     * 访问地址： http://www.fend1.com/index/excep?wxdebug=1
     *
     * @param Request $request
     * @param Response $response
     * @throws \Exception
     */
    public function excep(Request $request, Response $response)
    {
        //get parameter by query
        $userid = $request->get("userid");
        $username = $request->get("username");

        //test exception
        //抛出用户所见会白屏，http code 500 ，通过网址增加querystring wxdebug=1可以看到详细信息
        throw new \Exception("test", 1231);
    }

    /**
     * SQL query 演示
     * 如果请求querystring带wxdebug=1会看到更多信息
     * 访问地址： http://www.fend1.com/index/sql?wxdebug=1
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws \Exception
     */
    public function sql(Request $request, Response $response)
    {
        $val = $request->request();
        var_dump($val);
        //SQL查询测试，更多的可以在App\Test\Db看到
        $con = array(">" => ["id" => 0], "user_sex" => 1);
        $field = array("*");
        $start = 0;
        $limit = 20;

        ////////////////老方法实现查询
        $mod = Read::Factory("users", "default");
        $mod->setConditions($con);
        $mod->setField($field);
        $mod->setLimit($start, $limit);
        $sql = $mod->getSql();

        $q = $mod->query($sql);
        while ($rs = $mod->fetch($q)) {
            $result['list'][] = $rs;
        }
        $result['total'] = $mod->getSum();

        ////////////////链式调用展示
        $mod->clean()->setConditions($con)->setField($field)->setLimit($start, $limit)->query()->fetch_all();

        ////////////////链式调用展示
        $mod->clean()->where([["id", ">=", 0]])->setField($field)->setLimit($start, $limit)->query()->fetch_all();

        ////////////////直接使用read里面带的函数，并开启了prepare
        $mod->getListByCondition($con, $field, $start, $limit, "id desc", true);

        ////////////////直接使用read里面带的函数,where新条件设置方法，并开启了prepare
        $mod->getListByWhere([["id", ">=", 0]], $field, $start, $limit, "id desc", true);

        ////////////////model方式可以直接继承\Fend\App\DBModel 按表进行操作，可操作函数同Read|Write

        //设置返回cookie
        $response->cookie("kkdddd", "vvfdfdfd");

        $model = new DemoDBNCModel();
        $result1 = $model->getListByWhere([]);
        var_dump($result1);

        $result1 = $model->getLeftJoinListByWhere("user_info",["id"=>"user_id"],[]);
        var_dump("join",$result1);
        //$redis = Cache::factory(Cache::CACHE_TYPE_XESREDIS,"test");
        //var_dump("redisSdkTest", $redis->set("yes","11"));

        $result["中文"]="中文测试\\";
        return $response->json($result);
    }


    public function testupdate(Request $request, Response $response)
    {
        $mod = Write::Factory("users", "default");
        $sql = $mod->subSQL(["create_time" => ["+", 1]], $this->_table, 'update', "id > 7", []);
        var_dump($sql);
        $id = $mod->editById(7,["create_time" => ["+", 1]], true);
        var_dump($mod->getLastSQL());
        return "Ok";
    }

    public function status(Request $request, Response $response)
    {
        return $response->json(\Swoole\Coroutine::stats());
    }

    public function test()
    {
        $time = microtime(true);
        $ret = FendHttp::getUrl("127.0.0.1/111");
        $ret = FendHttp::getUrl("127.0.0.1/111");
        $ret = FendHttp::getUrl("127.0.0.1/111");
        $ret = FendHttp::getUrl("127.0.0.1/");
        return bcsub(microtime(true), $time, 4);
    }

    /**
     * csv输出演示
     * @param Request $request
     * @param Response $response
     * @throws \Exception
     */
    public function csv(Request $request, Response $response)
    {
        $head = [
            "序列",
            "中文",
        ];
        $response->csvHead($head);

        $data = [
            ["1", "中文1\"df"],
            ["2", "中文2'dfe"],
            ["3", "中文3\\"],
            ["4", "中文4\\\\"],
            ["5", "中文5\r\n"],
            ["6", "中文6"],
        ];
        $response->csvAppendWrite($data);
        $response->break("");
    }


    public function UnInit(Request $request, Response $response, &$result)
    {
        //$result = $result . "\r\n Uninit append text";
    }
}
