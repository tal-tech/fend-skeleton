<?php
namespace App\Http\Tal;

/**
 * Class Index
 * 首页
 * @author gary
 */
class Index
{

    /**
     * 初始化模板
     */
    public function index()
    {
        echo "this is test";
        \Fend\Logger::write("write an single file");
        return time();
    }
}
