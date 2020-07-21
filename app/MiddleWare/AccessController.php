<?php

namespace App\MiddleWare;


use Fend\Di;
use Fend\Router\Middleware\MiddlewareInterface;
use Fend\Router\Middleware\RequestHandler;

/**
 * js跨域中间件演示，启用需要在Router配置中开启
 * Class AccessController
 * @package App\MiddleWare
 */
class AccessController implements MiddlewareInterface
{

    public function process($request, RequestHandler $handler)
    {

        $response = Di::factory()->getResponse();
        $response->header("Access-Control-Allow-Origin", "*");
        $response->header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE");
        $response->header("Access-Control-Allow-Headers", "x-requested-with,content-type");

        $result = $handler->handle($request);

        return $result;
    }
}