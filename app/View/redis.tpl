{include file='./head.tpl'}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$baseDir}/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Redis</li>
    </ol>
</nav>
<div class="row">
    <div class="col-3">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list"
               href="#list-home" role="tab" aria-controls="home">String</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list"
               href="#list-profile" role="tab" aria-controls="profile">List</a>
            <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list"
               href="#list-messages" role="tab" aria-controls="messages">ZSet</a>
            <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list"
               href="#list-settings" role="tab" aria-controls="settings">HashSet</a>
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                <h3>String</h3>

                <pre><code class="language-php">//set 100x
$redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
for ($i = 0; $i < 100; $i++) {
    $redis->set("string-$i", mt_rand(1, 10000));
    $result[$i] = $redis->get("string-$i");
}

//get 100x
for ($i = 0; $i < 100; $i++) {
    $result[$i] = $redis->get("string-$i");
}
</code></pre>
                <hr/>

                <button type="button" class="btn btn-primary " onclick="setString()">Set 100X</button>
                <button type="button" class="btn btn-success " onclick="getString()">Get 100X</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result1" class="col-12 result"></div></pre>
                <script>
                    function setString() {
                        requestServer("/redis/setstring", "result1");
                    }

                    function getString() {
                        requestServer("/redis/getstring", "result1");
                    }
                </script>
            </div>

            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                <h3>List</h3>

                <pre><code class="language-php">//lpush
$redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
$result = $redis->lpush("list-test", mt_rand(1, 10000));

//lpop
$result = $redis->lPop("list-test");
</code></pre>
                <hr/>

                <button type="button" class="btn btn-primary " onclick="lpush()">lPush</button>
                <button type="button" class="btn btn-success " onclick="lpop()">lPop</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result2" class="col-12 result"></div></pre>
                <script>
                    function lpush() {
                        requestServer("/redis/lpush", "result2");
                    }

                    function lpop() {
                        requestServer("/redis/lpop", "result2");
                    }
                </script>
            </div>
            <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
                <h3>SortSet</h3>

                <pre><code class="language-php">//zAdd
$redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
$result = $redis->zAdd("sortset-test", mt_rand(1,1000), 'ok' . mt_rand(1,1000));

//zrange
$result = $redis->zRange("sortset-test", 0, -1, true);
</code></pre>
                <hr/>

                <button type="button" class="btn btn-primary " onclick="zadd()">zAdd</button>
                <button type="button" class="btn btn-success " onclick="zrange()">zRange</button>
                <button type="button" class="btn btn-danger " onclick="zdel()">Del</button>


                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result3" class="col-12 result"></div></pre>
                <script>
                    function zadd() {
                        requestServer("/redis/zadd", "result3");
                    }

                    function zrange() {
                        requestServer("/redis/zrange", "result3");
                    }

                    function zdel() {
                        requestServer("/redis/zdel", "result3");
                    }
                </script>
            </div>
            <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
                <h3>HashSet</h3>

                <pre><code class="language-php">//hset
$redis = Cache::factory(Cache::CACHE_TYPE_REDIS, "default");
$result = $redis->hset("hset-test", "key" . mt_rand(1, 2000), mt_rand(1, 2000));

//hgetall
$result = $redis->hGetAll("hset-test");

//hdel
$result = $redis->del("hset-test");
</code></pre>
                <hr/>
                <button type="button" class="btn btn-primary " onclick="hset()">hSet</button>
                <button type="button" class="btn btn-success " onclick="hgetall()">hGetAll</button>
                <button type="button" class="btn btn-danger " onclick="hdel()">Del</button>


                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result4" class="col-12 result"></div></pre>
                <script>
                    function hset() {
                        requestServer("/redis/hset", "result4");
                    }

                    function hgetall() {
                        requestServer("/redis/hgetall", "result4");
                    }

                    function hdel() {
                        requestServer("/redis/hdel", "result4");
                    }
                </script>
            </div>
        </div>
    </div>
</div>


{include file='./foot.tpl'}
