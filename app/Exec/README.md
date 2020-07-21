### 启用Symfony Console

```bash
composer requrie symfony/console 4.3
```

###添加新命令方法

 * 创建命令定义文件放置在Exec\Command内，类必须继承baseCommand
 * 类内定义 名称signature 和 desc 用途
 * 命令格式需要定义 params 变量 一个配置一行，配置格式为 配置键名|是否可选|参数说明 具体可以看swoole command
 * 针对命令的调用都会请求到handle内自行处理

###例子展示

 * 例如：Exec\Command\Swoole 演示
 ```
   Swoole的param定义为：
       "configFile|required|配置文件",//配置文件路径（必填）
       "operation|optional|swoole services", //操作（可选）
       "mode|optional|debug?",//（可选选项）
       "pidFile|optional|pid file" //指定pid文件路径(可选)
   
   命令执行样例：
   php Bin/fend list //列出所有可用模块
   php Bin/fend swoole -h //查看帮助
   php Bin/fend swoole start -c App/Config/Swoole.php //开启服务
   php Bin/fend swoole start -c App/Config/Swoole.php -d 1 //开启debug模式 服务
   
```
 命令处理会调用Handle函数进行处理
   