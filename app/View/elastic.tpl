{include file='./head.tpl'}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$baseDir}/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">ElasticSearch</li>
    </ol>
</nav>
<div class="row">
    <div class="col-3">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list"
               href="#list-create" role="tab" aria-controls="home">Create Index</a>
            <a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list"
               href="#list-home" role="tab" aria-controls="home">Insert Data</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list"
               href="#list-profile" role="tab" aria-controls="profile">Search</a>
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-create" role="tabpanel" aria-labelledby="list-home-list">
                <h3>Create Index</h3>

                <pre><code class="language-php">$setting = [
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
        ]
    ]
];

$es = ElasticSearch6::Factory("default");
$ret = $es->createIndex("k8s_test", $mapping, $setting);

</code></pre>
                <hr/>

                <button type="button" class="btn btn-primary " onclick="createTopic()">Create Index</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result0" class="col-12 result"></div></pre>
                <script>
                    function createTopic() {
                        requestServer("/elastic/create", "result0");
                    }

                </script>
            </div>

            <div class="tab-pane fade show" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                <h3>Index data</h3>

                <pre><code class="language-php">$es = ElasticSearch6::Factory("default");
$id = $es->indexDocument("k8s_test", "_doc", ["first_name" => "yes random " . mt_rand(100000,10000000), "age" => 12]);
</code></pre>
                <hr/>

                <button type="button" class="btn btn-primary " onclick="produce()">index doc</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result1" class="col-12 result"></div></pre>
                <script>
                    function produce() {
                        requestServer("/elastic/add", "result1");
                    }

                </script>
            </div>

            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                <h3>Search</h3>

                <pre><code class="language-php">$search = [
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
$ret = $es->search("k8s_test", "_doc", $search);
</code></pre>
                <hr/>

                <button type="button" class="btn btn-success " onclick="consumer()">Search</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result2" class="col-12 result"></div></pre>
                <script>
                    function consumer() {
                        requestServer("/elastic/search", "result2");
                    }
                </script>
            </div>


        </div>
    </div>
</div>
{include file='./foot.tpl'}
