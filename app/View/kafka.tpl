{include file='./head.tpl'}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$baseDir}/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kafka</li>
    </ol>
</nav>
<div class="row">
    <div class="col-3">
        <div class="list-group" id="list-tab" role="tablist">

            <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list"
               href="#list-home" role="tab" aria-controls="home">Produce</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list"
               href="#list-profile" role="tab" aria-controls="profile">Consumer && Ack</a>
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content" id="nav-tabContent">


            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                <h3>Produce Message</h3>

                <pre><code class="language-php">//produce
$queue = Queue::factory(Queue::QUEUE_TYPE_RDKAFKA, "default");
$result = $queue->pushQueue("test msg:" . mt_rand(1, 100000));
</code></pre>
                <hr/>

                <button type="button" class="btn btn-primary " onclick="produce()">Send Msg</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result1" class="col-12 result"></div></pre>
                <script>
                    function produce() {
                        requestServer("/kafka/produce", "result1");
                    }

                </script>
            </div>

            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                <h3>Consumer && Ack</h3>

                <pre><code class="language-php">//consumer && ack
$queue = Queue::factory(Queue::QUEUE_TYPE_RDKAFKA, "default");
$result = $queue->consumer();
if($result) {
    $queue->commitAck($result);
}
</code></pre>
                <hr/>

                <button type="button" class="btn btn-success " onclick="consumer()">Consumer && Ack</button>

                <hr/>
                Result:
                <!--alert-danger-->
                <pre><div id="result2" class="col-12 result"></div></pre>
                <script>
                    function consumer() {
                        requestServer("/kafka/consumer", "result2");
                    }
                </script>
            </div>


        </div>
    </div>
</div>
{include file='./foot.tpl'}
