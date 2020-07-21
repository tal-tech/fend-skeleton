<?php
namespace App\Model;

/**
 * 后台用户管理
 * @author gary
 **/
class User extends \Fend\Fend
{
    // 密码有效期为90天，超过有效期必须要修改一次密码
    const ADMIN_VALID_PWD = 7776000;

    // 30天未登陆过的用户，自动锁定
    const ADMIN_VALID_USERLOCK = 2592000;

    // 新开通账户7天未登陆自动锁定
    const ADMIN_VALID_USERLOCK_NEW = 604800;

    private $_table     = 'ucm_sysuser';
    private $_tableLock = 'ucm_syslock';
    private $_db        =  'ht';


    /**
     * 获取用户列表
     * @param  array     $con    条件
     * @param array $field  字段
     * @param int   $start  开始
     * @param int   $limit  总条数
     * @return array
     */
    public function getList($con=array(), $field=array(), $start=0, $limit=20)
    {
        $tmy    = array('list'=>array(),'total'=>0);
        $mod   = \Fend\Read::Factory($this->_table, $this->_db);
        $mod->setConditions($con);
        $mod->setField($field);
        $mod->setLimit($start, $limit);
        $sql = $mod->getSql();
        $q   = $mod->query($sql);
        while ($rs = $mod->fetch($q)) {
            $tmy['list'][] = $rs;
        }
        $tmy['total'] = $mod->getSum();
        return $tmy;
    }
    /**
     * 获取用户信息
     * @param   array    $con    条件
     * @param array $field  字段
     * @param int   $start  开始
     * @param int   $limit  总条数
     * @return array
     */
    public function getInfoByCondition($con=array(), $field=array(), $start=0, $limit=20)
    {
        $tmy    = array('list'=>array(),'total'=>0);
        $mod   = \Fend\Read::Factory($this->_table);
        $mod->setConditions($con);
        $mod->setField($field);
        return $mod->getOne();
    }

    /**
     * 添加&修改
     *
     * @param array $item 添加话题信息
     * @return int 栏目
     **/
    public function add($item)
    {
        $rs=array(
            'id'        =>0,//信息ID
            'name'      =>'',//账户
            'email'     =>'',//邮箱
            'nickname'  =>'',//昵称
            'pwd'       =>'',//密码
            'tpower'    =>'',//权限
            'phone'     =>0,//手机号
            'gid'       =>'',//分组
        );

        //连接DB
        $db= \Fend\Write::Factory($this->_table, $this->_db);

        //赋值集合数据
        foreach ($rs as $k=>$v) {
            if (isset($item[$k])) {
                $rs[$k]=is_numeric($v) ? (int)$item[$k] : $db->escape(stripslashes($item[$k]));
            } else {
                unset($rs[$k]);
            }
        }
        //我很努力的发掘，但还是为找到符合表的字段
        if (count($rs)<=0) {
            return 0;
        }
        if (!empty($rs['id'])) {
            $rs['ctime'] = time();
            $sql = $db->subSQL($rs, $this->_table, 'insert');
            $db->query($sql);
            $rs['id'] = $db->getLastId();
        } else {
            $rs['utime'] = time();
            $sql = $db->subSQL($rs, $this->_table, 'update', " id={$rs['id']}");
            $db->query($sql);
        }
    }
}
