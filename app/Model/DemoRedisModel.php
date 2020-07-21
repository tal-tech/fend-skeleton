<?php

namespace App\Model;

use Fend\App\RedisModel;
use Fend\Cache;

class DemoRedisModel extends RedisModel
{
    protected $_config = "default";

    protected $_dbType = Cache::CACHE_TYPE_REDIS;

    public function recordUser()
    {
        $id = mt_rand(12222, 3333333);
        //获取互斥锁, 如果抢到，这个锁会在5秒后自动释放
        $ret = $this->lock("fend_test_" . $id, 5);
        if ($ret) {
            //开启pipline模式批量执行redis命令
            $this->startTransaction();

            $this->set("test_aaa", 123);
            $this->_db->incr("test_aaa");
            $this->_db->incr("test_aaa");
            $this->_db->incr("test_aaa");

            //执行pipline
            $result = $this->commitTransaction();
            //锁用完释放掉
            $this->unlock("fend_test_" . $id);
            return $result;
        }

        //锁用完释放掉
        $this->unlock("fend_test_" . $id);
        return false;
    }

    /**
     * 下发任务给redis Queue(list)
     * 仅供参考，不提供数据安全保障
     * @return bool|int
     */
    public function sendQueue()
    {
        return $this->pushQueue("fend_queue_test", "yes");
    }

    /**
     * 获取任务从redis queue(list)
     * 仅供参考，不提供数据安全保障
     * 没有数据会等待5秒，超过没有数据返回false，有数据马上返回
     * @return array|false|string
     */
    public function getQueue()
    {
        return $this->popQueue("fend_queue_test", 5);
    }

    /**
     * 闭包方式实现lock锁定
     * @return mixed
     * @throws \Exception
     */
    public function callbackLock()
    {
        $count = 3;
        while ($count) {
            $count--;

            try{
                return $this->locked(function (){}, "lock_test_dd", 10);
            }catch (\Exception $e) {
                if($e->getCode() === -1) {
                    //lock fail retry
                    continue;
                }

                throw $e;
            }
        }

    }
}