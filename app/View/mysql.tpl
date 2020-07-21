{include file='./head.tpl'}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$baseDir}/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mysql</li>
    </ol>
</nav>

<div class="row">
    <div class="col-3">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list"
               href="#list-home" role="tab" aria-controls="home">Mysql Initialization</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list"
               href="#list-profile" role="tab" aria-controls="profile">CURD</a>
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                <h3>Mysql Initialization</h2>

                    <pre><code class="language-php">//Init Table
$write = Write::Factory("users", "default", "MysqlPDO");
$sql = "DROP table IF EXISTS users";
$ret = $write->query($sql);
//...

</code></pre>
                    <hr/>

                    <button type="button" class="btn btn-primary " onclick="inittable()">Init Demo DB</button>

                    <hr/>
                    Result:
                    <!--alert-danger-->
                    <pre><div id="result1" class="col-12 result"></div></pre>
                    <script>
                        function inittable() {
                            requestServer("/mysql/inittable", "result1");
                        }

                    </script>
            </div>

            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                <h3>CURD</h3>

                <pre style="overflow: auto; height: 400px;"><code class="language-php">//Read Obj Query
$result = [];
$read = Read::Factory("users", "default", "MysqlPDO");
$result = $read->getListByWhere([], [], 0, 100, "id desc");

//Read Obj Query by SQL
$sql = ""select * from users order by id desc limit 0, 100"";
$result = $read->query($sql);

//Write Obj Insert
$write = Write::Factory("users", "default", "MysqlPDO");
$result = $write->add([
    "account" => "user" . mt_rand(1, 1000),
    "passwd" => uniqid(),
    "user_sex" => mt_rand(0, 2),
    "user_name" => "user" . mt_rand(1, 1000),
    "create_time" => time(),
    "update_time" => time()
    ],
    true //auto prepare
);

//delete by where
$result = $write->delByWhere([["id", ">", 6]]);
</code></pre>
                <hr/>
                <button type="button" class="btn btn-success" onclick="writeInsert()">Write-Insert</button>
                <button type="button" class="btn btn-primary" onclick="readQuery()">Read-Query</button>
                <button type="button" class="btn btn-danger " onclick="writeDelete()">Write-Delete</button>
                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result2" class="col-12 result"></div></pre>
                <script>
                    function readQuery() {
                        requestServer("/mysql/readQuery", "result2");
                    }

                    function writeInsert() {
                        requestServer("/mysql/writeInsert", "result2");
                    }

                    function writeDelete() {
                        requestServer("/mysql/writeDelete", "result2");
                    }
                </script>
            </div>


        </div>
    </div>
</div>

{include file='./foot.tpl'}
