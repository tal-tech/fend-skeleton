# bin 目录功能说明
## cleancache.sh
清理框架文件缓存，一般htmlpuffiy插件、fastrouter、smarty的cache都会放在app\Cache目录内，如果有使用以上组件发版时请清理一下cache内内容 

## composer
最新版composer方便用户直接使用 

## fend
使用symfony console组件命令入口，可以启动重启swoole服务，其他用户命令可以放到app\Exec内即可提供服务 

## formatcode.sh
和php-cs-fixer搭配使用，如果没有自带psr2格式化代码的用户可以使用这个脚本格式化代码缩进，用于命令行下特殊情况需要整理代码 

## generator
由于symfony console有一段时间没有维护，这个功能暂时放外面了，这个是fend-plugin-errorcode组件的辅助功能，可以将用户定义的所有错误码生成markdown文件供开发时和用户参考 

## optimizeComposer.sh
php的自动加载和composer的自动加载上线后建议用这个脚本优化一下，每次发版都执行一下，会提高QPS 10%左右 

## phpunit 
单元测试使用

## start.php
swoole server启动封装，用于如果symfony console组件没有装的话，使用这个命令启动，并且这个启动的server才支持大部分内容的reload
