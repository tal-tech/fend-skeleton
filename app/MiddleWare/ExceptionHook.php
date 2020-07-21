<?php

namespace App\Middleware;

use Fend\Di;
use Fend\Exception\ExitException;
use Fend\Logger;
use Fend\Router\Middleware\MiddlewareInterface;
use Fend\Router\Middleware\RequestHandler;

class ExceptionHook implements MiddlewareInterface
{

    public function process($request, RequestHandler $handler)
    {
        $request = Di::factory()->getRequest();

        //请求Controller，如果发生异常，那么返回结果
        try {
            //执行controller
            $result = $handler->handle($request);
        } catch (ExitException $e) {
            //正常退出直接输出内容
            $result = $e->getData();
        } catch (\Throwable $e) {
            //异常情况接口返回内容
            $result = json_encode([
                "code" => $e->getCode(),
                "msg" => $e->getMessage(),
            ]);

            //记录异常日志
            Logger::exception(basename($e->getFile()), $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), $e->getTraceAsString(), array(
                "action" => $request->header("HOST") . $request->server("REQUEST_URI"),
                "server_ip" => gethostname(),
            ));

        }
        //请求后置操作
        //返回结果
        return $result;
    }
}