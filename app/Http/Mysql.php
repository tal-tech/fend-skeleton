<?php

namespace App\Http;

use Fend\Read;
use Fend\Write;

class Mysql
{

    public function initTable()
    {
        try {
            $result = [];

            $db = Write::Factory("users", "default", "MysqlPDO");

            $sql = "DROP table IF EXISTS users";
            $ret = $db->query($sql);

            $sql = "DROP table IF EXISTS user_info";
            $ret = $db->query($sql);

            $sql = "create table if not exists users(
    id int not null auto_increment,
    account varchar(30) not null,
    passwd varchar(30) not null,
    user_sex tinyint(4),
    user_name varchar(30) not null,
    create_time int,
    update_time int,
    primary key (id)
)";
            $ret = $db->query($sql);

            $sql = "insert into users
(id, account, passwd, user_sex, user_name, create_time,update_time)
values
    (3,'user1','pwd',1,'hehe1',1563518812,1563518812),
    (4,'user2','pwd',2,'测试',1563518812,1563518812),
    (5,'user3','pwd',1,'hehe3',1563518812,1563518812),
    (6,'user4','pwd',0,'hehe4',1563518812,1563518812)";
            $ret = $db->query($sql);


            $sql = "create table if not exists user_info(
    id int not null auto_increment,
    user_id int,
    score int,
    gold int,
    primary key (id)
)";
            $ret = $db->query($sql);

            $sql = "insert into user_info
(id, user_id, score, gold)
values
    (3,3,10,1),
    (4,4,1222,2),
    (5,6,200,1),
    (6,5,123,0);";
            $ret = $db->query($sql);

            if ($ret) {
                $ret = true;
            } else {
                $ret = false;
            }
            return json_encode(["code" => 1, "msg" => "OK", "data" => $ret], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }

    }

    public function readQuery()
    {
        try {
            $result = [];
            $read = Read::Factory("users", "default", "MysqlPDO");
            $result = $read->getListByWhere([], [], 0, 100, "id desc");

            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function writeInsert()
    {
        try {
            $result = [];
            $write = Write::Factory("users", "default", "MysqlPDO");
            $result = $write->add([
                "account" => "user" . mt_rand(1, 1000),
                "passwd" => uniqid(),
                "user_sex" => mt_rand(0, 2),
                "user_name" => "user" . mt_rand(1, 1000),
                "create_time" => time(),
                "update_time" => time()
            ], true
            );

            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function writeDelete()
    {
        try {
            $result = [];
            $write = Write::Factory("users", "default", "MysqlPDO");
            $result = $write->delByWhere([["id", ">", 6]]);

            return json_encode(["code" => 1, "msg" => "OK", "data" => $result], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }
}