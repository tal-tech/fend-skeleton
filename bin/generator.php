<?php
declare(strict_types=1);

/**
 * fend-plugin-errorcode 错误码生成字典
 */
define('CMD_ERROR', 'error');

function usage()
{
    echo "Usage: ./generator -a error -c ./App/Const/ModuleDefine.php -o ./output\n";
    echo "-a 要执行的命令，支持：error(错误码文档)\n";
    echo "-c 依赖的配置文件\n";
    echo "-o 生成文档的保存文件名（带路径）";
    exit;
}

function generateError(array $params)
{
    $output = "# ErrorCode列表\n";
    $output .= PHP_EOL;

    $moduleList = include $params['config'];

    foreach ($moduleList as $key => $moduleFile) {

        $fileNames = explode('/', $moduleFile);

        $filename = $fileNames[count($fileNames) - 1];

        $filename = explode(".", $filename)[0];

        $output .= "## " . $filename . PHP_EOL;
        $output .= PHP_EOL;
        $output .= "module code: " . $key. PHP_EOL;
        $output .= PHP_EOL;
        $codeList = include $moduleFile;

        foreach ($codeList as $code => $msg) {
            $output .= sprintf("* %s-%s %s\n" , $key, $code, $msg);
            $output .= PHP_EOL;
        }
    }

    file_put_contents($params['output'] . '.md', $output);
}


$options = getopt("a:c:o:");

$args = [];

foreach ($options as $option => $value)
{
    switch ($option)
    {
        case 'a':
        {
            switch ($value) {
                case CMD_ERROR: {
                    $function = 'generateError';
                    break;
                }
                default: {
                    usage();
                }
            }
            break;
        }
        case 'c': {
            $args['config'] = $value;
            break;
        }
        case 'o': {
            $args['output'] = $value;
            break;
        }
        default: {
            usage();
        }
    }
}
if (empty($function)) {
    usage();
}
if (!isset($args['config'])) {
    usage();
}
if (!isset($args['output'])) {
    usage();
}
call_user_func($function, $args);