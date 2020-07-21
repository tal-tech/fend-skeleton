<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fend Demo</title>
    <link rel="stylesheet" href="{$baseDir}/css/bootstrap.css">
    <link rel="stylesheet" href="{$baseDir}/css/prism.css">

    <script src="{$baseDir}/js/http_code.jquery.com_jquery-3.4.1.js"></script>
    <script src="{$baseDir}/js/http_cdn.jsdelivr.net_npm_popper.js@1.16.0_dist_umd_popper.js"></script>
    <script src="{$baseDir}/js/http_stackpath.bootstrapcdn.com_bootstrap_4.4.1_js_bootstrap.js"></script>
    <script src="{$baseDir}/js/bootstrap.bundle.min.js"></script>
    <script src="{$baseDir}/js/prism.js"></script>
    <script src="{$baseDir}/js/notify.min.js"></script>
    <script type="text/javascript">
        function tips(msg, type) {
            $.notify(msg, type);
        }

        function requestServer(url, result_id, data) {
            $.ajax({
                url: "{$baseDir}" + url,
                cache: false,
                method: "get",
                async: true,
                timeout: 3000,
                data: data,
                success: function (data) {
                    $("#" + result_id).html(data + "<br/><br/>");
                    if (data === "") {
                        //tips("Server Error \nServer response is empty!", "error");
                        return;
                    }
                    try {
                        result = eval("(" + data + ")");
                    } catch (err) {
                        //tips("Server Error \nResponse Content was not json:" + err.message + " code:" + err.code, "error");
                    }

                    if (typeof (result.code) === "undefined" || typeof (result.msg) === "undefined") {
                        //tips("Server Response format Incorrect", "error");
                    } else {
                        if (result.code !== 1) {
                            //tips("Request Fail \nURL:" + url + " \nError Message:" + result.msg + " \nCode:" + result.code, "error");
                        } else {
                            tips("Request Success \nURL:" + url, "info");
                        }
                    }
                },
                error: function ($data) {
                    //tips("Request Fail!\nRequest Timeout", "error");
                    $("#" + result_id).html("接口请求超时");
                }
            });
        }
    </script>
    <style type="text/css">
        .result {
            background-color: black;
            color: white;
            min-height: 200px;
            overflow-x: auto;
            border-radius: 5px;
            -moz-border-radius: 5px;
        }

    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="{$baseDir}/" data-toggle="tooltip" data-placement="bottom" title="Fend框架"><img
                    src="{$baseDir}/img/fend.png" style="width: 100px;"/></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{$baseDir}/index/redis">Redis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$baseDir}/index/mysql">MySQL</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$baseDir}/index/kafka">Kafka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$baseDir}/index/elastic">ElasticSearch</a>
                </li>

            </ul>
        </div>
    </nav>
