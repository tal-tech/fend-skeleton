<?php
namespace App\Services;

/**
 * 用户登录
 * @author gary
 **/
class User extends \Fend\Fend
{
    public static function factory()
    {
        return new self();
    }


    public function checkLogin()
    {
        $userinfo = array();
        if (empty($_COOKIE['ht'])) {
            return false;
        } else {
            $redis      = \Fend\Cache::factory(\Fend\Cache::CACHE_TYPE_REDIS);
            $authorkey  = $_COOKIE['ht'];
            $userinfo   = $redis->get('user_'.$authorkey);
        }
        $this->reg  = $userinfo;
        return $userinfo;
    }
}
