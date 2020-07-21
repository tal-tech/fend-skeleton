<?php

namespace Installer;

class install
{
    private static $core = [
        "php/fend-core" => ["desc" => "Fend FrameWork Core"],
        "php/fend-plugin-router" => ["desc" => "Fend Default Router"],
    ];

    private static $plugin = [
        "php/fend-plugin-fastrouter" => ["desc" => "FastRouter is an extended plugin for RegExp url router"],

        "php/fend-plugin-db" => ["desc" => "SQL Builder and Mysqli\\PDO_mysql driver Factory"],
        "php/fend-plugin-mysqli" => ["desc" => "Mysql Extension Mysqli driver. connection pool on coroutine"],
        "php/fend-plugin-pdo" => ["desc" => "Mysql Extension PDO driver. connection pool on coroutine"],

        "php/fend-plugin-cache" => ["desc" => "Cache is Redis\\Memcache driver Factory"],
        "php/fend-plugin-redis" => ["desc" => "Redis Driver with retry. connection pool on coroutine"],
        "php/fend-plugin-memcache" => ["desc" => "Memcache Driver with retry. connection pool on coroutine"],
        "php/fend-plugin-redismodel" => ["desc" => "Redis Model plugin for manage the mass Redis key"],

        "php/fend-plugin-queue" => ["desc" => "Queue is Factory for Kafka\\RabbitMQ sidecar driver"],
        "php/fend-plugin-kafka" => ["desc" => "Kafka API Gateway driver"],
        "php/fend-plugin-rabbitmq" => ["desc" => "RabbitMQ driver"],

        "php/fend-plugin-validator" => ["desc" => "Validator for request param validator plugin"],

        "php/fend-plugin-server" => ["desc" => "Swoole server for smooth switch fpm"],

        "php/fend-plugin-console" => ["desc" => "Symfony console for console cmd manage"],
        "php/fend-plugin-template" => ["desc" => "Template with smarty and php native"],
        "php/fend-plugin-errorcode" => ["desc" => "Error code dict manage plugin"],
        "php/fend-plugin-elasticsearch" => ["desc" => "ElasticSearch sdk for op es 6.x 7.x"],
        //"php/fend-plugin-xhprof" => ["desc"=> ""],
        "php/fend-plugin-alarm" => ["desc" => "System error alram sdk for xiaotiaoquan"],
        "php/fend-plugin-env" => ["desc" => ".env file on root of project for env manage"],
    ];

    public function __construct()
    {
    }

    /**
     * 系统安装脚本
     * @param $event
     */
    public static function install($event)
    {

        //check composer
        if (!file_exists("./composer.json")) {
            echo "config not found ./composer.json !\n";
            exit;
        }
        echo Color::colorFormat("load composer.json file :" . realpath("./composer.json"), Color::STYLE_BRIGHT, Color::FONT_BLUE) . "\n";

        //load composer setting
        $composer = json_decode(file_get_contents("./composer.json"), true);

        //load core composer
        foreach ($composer["require"] as $name => $version) {
            if (isset(self::$core[$name])) {
                self::$core[$name]["select"] = true;
                self::$core[$name]["version"] = $version;
            }
        }

        //load selected composer
        foreach ($composer["require"] as $name => $version) {
            if (isset(self::$plugin[$name])) {
                self::$plugin[$name]["select"] = true;
                self::$plugin[$name]["version"] = $version;
            }
        }

        //start copyright
        echo Color::colorFormat("
===========================================================
 Fend FrameWork Installer v1.2
===========================================================

 Fend FrameWork is Tal Internal Open Source PHP Framework
 Welcome to participate in open source construction
  
===========================================================
 Copyright Tal Education Group 2020 All Rights Reserved.
===========================================================\n");

        //coroutine open
        $version = self::askQuestion("Do you want Coroutine?", ["y" => "1.3", "n" => "1.2", "default" => "n"]);
        echo Color::colorFormat("set version to $version", Color::STYLE_BRIGHT, Color::BG_GREEN) . "\n";

        //add core plugin
        foreach (self::$core as $name => $option) {
            if (isset(self::$core[$name]["version"])) {
                $composer["require"][$name] = self::$core[$name]["version"];
            } else {
                $composer["require"][$name] = "~" . $version . ".0";
            }
        }

        //plugin list?
        while (1) {
            $id = 0;
            $selectOption = [];
            $mask = " %2.2s [%1.1s]%-47.47s | %-80.80s |";

            echo "==============================================================================================================\n";
            echo " Fend Framework plugin list\n";
            echo "==============================================================================================================\n";
            foreach (self::$plugin as $name => $option) {
                $id++;
                $selectOption[$id] = $name;

                echo Color::colorTable($mask, [
                    $id,
                    isset(self::$plugin[$name]["select"]) && self::$plugin[$name]["select"] == 1 ? "x" : "",
                    Color::colorFormat("$name", Color::STYLE_BRIGHT, Color::FONT_GREEN),
                    Color::colorFormat($option["desc"] . "", Color::STYLE_BLINK, Color::FONT_WHITE),
                ]);
                echo "\n";

            }

            //set q to exit
            echo Color::colorTable($mask, [
                "q",
                "",
                Color::colorFormat("finished select", Color::STYLE_BRIGHT, Color::FONT_GREEN),
                Color::colorFormat("Type q to continue build", Color::STYLE_BLINK, Color::FONT_WHITE),
            ]);
            echo "\n";

            $selectOption["q"] = "exit";

            //ask question
            $select = self::askQuestion("select plugin you want", $selectOption, "1-$id q to finished");

            //user type x to exit
            if ($select === "exit") {
                break;
            }

            //select plugin and open option
            if (isset(self::$plugin[$select])) {
                echo Color::colorFormat("select " . $select . " plugin", Color::STYLE_BRIGHT, Color::FONT_MAGENTA);
                echo "\n";
                self::$plugin[$select]["select"] = self::$plugin[$select]["select"] ?? false;
                self::$plugin[$select]["select"] = !self::$plugin[$select]["select"];

                if (self::$plugin[$select]["select"]) {
                    //depend package auto select
                    switch ($select) {
                        //db
                        case "php/fend-plugin-mysqli":
                        case "php/fend-plugin-pdo":
                            self::$plugin["php/fend-plugin-db"]["select"] = true;
                            echo Color::colorFormat("append depend plugin php/fend-plugin-db", Color::STYLE_BRIGHT, Color::FONT_MAGENTA) . "\n";
                            break;
                        //cache
                        case "php/fend-plugin-redis":
                        case "php/fend-plugin-memcache":
                        case "php/fend-plugin-redismodel":
                            self::$plugin["php/fend-plugin-cache"]["select"] = true;
                            echo Color::colorFormat("append depend plugin php/fend-plugin-cache", Color::STYLE_BRIGHT, Color::FONT_MAGENTA) . "\n";
                            break;
                        //queue
                        case "php/fend-plugin-kafka":
                        case "php/fend-plugin-rabbitmq":
                            self::$plugin["php/fend-plugin-queue"]["select"] = true;
                            echo Color::colorFormat("append depend plugin php/fend-plugin-queue", Color::STYLE_BRIGHT, Color::FONT_MAGENTA) . "\n";
                            break;

                    }
                }
            }

        }

        echo "\n";

        //show select plugin
        echo Color::colorFormat("Finished Plugin List:", Color::STYLE_BRIGHT, Color::FONT_YELLOW);
        echo "\n";

        foreach (self::$plugin as $name => $option) {
            if (isset(self::$plugin[$name]["select"]) && self::$plugin[$name]["select"] == true) {
                //set the compsoer require with plugin
                if (isset(self::$plugin[$name]["version"])) {
                    $composer["require"][$name] = self::$plugin[$name]["version"];
                    $ver = self::$plugin[$name]["version"];
                } else {
                    $composer["require"][$name] = "~" . $version . ".0";
                    $ver = "~" . $version . ".0";
                }

                echo "  ";
                echo Color::colorFormat($name, Color::STYLE_BRIGHT, Color::FONT_CYAN);
                echo Color::colorFormat($ver, Color::STYLE_BRIGHT, Color::FONT_MAGENTA);
                echo "\n";
            }
        }

        //clean up skeleton info and script
        unset($composer["name"]);
        unset($composer["description"]);
        unset($composer["type"]);
        unset($composer["scripts"]["pre-install-cmd"]);
        unset($composer["scripts"]["pre-update-cmd"]);
        unset($composer["autoload"]["psr-4"]["Installer\\"]);

        //add autoload psr4 for project
        $composer["autoload"]["psr-4"]["App\\"] = "./app";

        //end
        //sync composer
        $ret = file_put_contents("./composer.json", json_encode($composer, JSON_PRETTY_PRINT));
        if (!$ret) {
            echo Color::colorFormat("Error write ./composer.json fail! please check permission of composer.json", Color::STYLE_BRIGHT, Color::FONT_RED) . "\n";
            exit;
        }
        echo Color::colorFormat("update composer.json completed", Color::STYLE_BRIGHT, Color::FONT_WHITE) . "\n";


    }

    /**
     * 询问用户选项
     * @param $question
     * @param $option
     * @return string
     */
    public static function askQuestion($question, $option = ["y" => true, "yes" => true, "n" => false, "no" => false,
        "default" => "yes"], $tips = "")
    {
        //show all option on input
        if (is_array($option) && $tips === "") {
            $opt = $option;
            unset($opt["default"]);
            $question = $question . " [" . implode("/", array_keys($opt)) . "]";
        }

        if ($tips !== "") {
            $question = $question . " [$tips]";
        }

        if (isset($option["default"])) {
            $question = $question . " [" . $option["default"] . "]";
        }

        while (1) {
            echo Color::colorFormat($question . ":", Color::STYLE_BRIGHT, 32, 40);
            $input = self::waitInput();

            //no input and have default
            if ($input === "" && isset($option["default"]) && isset($option[$option["default"]])) {
                return $option[$option["default"]];
            }

            //use user input string
            if ($input !== "" && $option === "input") {
                return $input;
            }

            //ok user fill option
            if ($input !== "") {
                //ok standard input answer
                if (isset($option[$input])) {
                    return $option[$input];
                }
                //other retry input
                continue;
            }

            //retry
            continue;
        }

    }

    /**
     * 等待用户输入
     * @return string
     */
    public static function waitInput()
    {
        $handle = \fopen("php://stdin", "r");
        $line = \fgets($handle);
        fclose($handle);
        return trim($line ?? "");
    }
}
