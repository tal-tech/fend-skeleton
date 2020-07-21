<?php

namespace App\Http;

use Fend\ElasticSearch\ElasticSearch6;

class Elastic
{
    public function create()
    {
        $setting = [
            'number_of_shards' => 3,
            'number_of_replicas' => 2
        ];

        $mapping = [
            '_source' => [
                'enabled' => true
            ],
            'properties' => [
                'first_name' => [
                    'type' => 'text',
                ],
                'age' => [
                    'type' => 'integer'
                ],
                '@timestamp' => [
                    'type' => 'timestamp'
                ]
            ]
        ];

        $es = ElasticSearch6::Factory("default");
        try {
            $ret = $es->createIndex("k8s_test", $mapping, $setting);
            return json_encode(["code" => 1, "msg" => "OK", "data" => $ret], JSON_PRETTY_PRINT);
        } catch (\Throwable $e) {
            if($e->getCode() == 400) {
                return json_encode(["code" => 400, "msg" => "k8s_test index exist", "data" => []], JSON_PRETTY_PRINT);
            }
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function add()
    {
        $es = ElasticSearch6::Factory("default");

        $id = "";
        try {
            $id = $es->indexDocument("k8s_test", "_doc", ["first_name" => "yes random " . mt_rand(100000,10000000), "age" => 12, "timestamp" => time() * 1000]);
            return json_encode(["code" => 1, "msg" => "OK", "data" => $id], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }
    }

    public function search()
    {
        $search = [
            'query' => array(
                'bool' => array(
                    'must'     => array(
                        array(
                            'query_string' => array(
                                'query'            => "yes",
                                'analyze_wildcard' => true,
                                'default_field'    => '*',
                            ),
                        ),
                    ),
                ),
            ),
            'size'  => 20,

        ];
        $es = ElasticSearch6::Factory("default");

        try {
            $ret = $es->search("k8s_test", "_doc", $search);
            return json_encode(["code" => 1, "msg" => "OK", "data" => $ret["hits"]], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return json_encode(["code" => $e->getCode(), "msg" => $e->getMessage(), "data" => []], JSON_PRETTY_PRINT);
        }

    }
}