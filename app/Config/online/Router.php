<?php
//新路由解析
////////////////////////////////////////////////////////////
//注意：新路由修改后,记得清空cache/router内缓存文件
////////////////////////////////////////////////////////////

return [

    //fast router配置
    //不同域名可以映射不同App\Http下子目录,若没有，默认以\app\http为开始查找
    //限制规则：App\Http 内文件名必须首字母大写，其他字母皆为小写
    //启用fastrouter，composer需要引入这个组件方可使用 composer require nikic/fast-route 1.3
    "map" => [
        //没有指定域名的请求访问的路径
        'default'     => [
            'root'       => "\\App\\Http",//namespace
            'direct'     => true,//如果没有router匹配，那么继续按路径进行路由
            "fastrouter" => false,//启用fastrouter，composer需要引入这个组件方可使用 composer require nikic/fast-route 1.3
            'open_cache'  => false,//如果启用fastrouter，可以通过该配置设置是否使用缓存文件，开发环境建议设置为false
            //fastrouter映射
            'router'     => [
                //method   url  ControllerNameSpace@ControllerFunctionName
                //类型，网址，controller路径@函数名
                //暂时只支持callable和ControllerName@functionName方式，回调方式封禁
                ['GET', '/test', '\App\Http\Index@index'],
                ['POST', '/test', '\App\Http\User@getList'],
                ['GET', '/test/{id:\d+}/{name}', '\App\Http\User@getInfo'],
            ],
        ],
        'www.fend.com' => [
            'root'       => "\\App\\Http\\Tal",
            'direct'     => true,//如果没有router匹配，那么继续按路径进行路由
            "fastrouter" => false,//启用fastrouter
            'open_cache'  => false,//如果启用fastrouter，可以通过该配置设置是否使用缓存文件，开发环境建议设置为false
            //fastrouter映射
            'router'     => [
                ['GET', '/test1', '\App\Http\Index@index'],
                ['POST', '/test1', '\App\Http\User@getList'],
                ['GET', '/test1/{id:\d+}/{name}', '\App\Http\User@getInfo'],
            ],
        ],
    ],
];