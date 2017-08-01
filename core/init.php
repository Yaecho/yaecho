<?php
namespace core;

require_once YAECHO_PATH.'/core/yaecho/AutoLoader.php';

use core\yaecho\AutoLoader;
use core\yaecho\Yaecho;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Noodlehaus\Config;

// Config
$GLOBALS['config'] = new Config(YAECHO_PATH. '/config/conf.php');

// Whoops
if($GLOBALS['config']->get('debug')) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
} else {
    //logger
    $log = new Logger('system');
    $log->pushHandler(new StreamHandler(YAECHO_PATH.'/runtime/log/'.date('Y-m-d', time()).'.log', Logger::DEBUG));

    // 日志消息等级
    $logLevel = array(
        "EMERGENCY",// = LOG_EMERG;    // 0
        "ALERT",     //= LOG_ALERT;    // 1
        "CRITICAL", //  = LOG_CRIT;     // 2
        "ERROR", //     = LOG_ERR;      // 3
        "WARNING", //   = LOG_WARNING;  // 4
        "NOTICE", //    = LOG_NOTICE;   // 5
        "INFO",    //  = LOG_INFO;     // 6
        "STRACE",    //= 7;
        "DEBUG",     //= 8;
    );

    set_exception_handler(function ($e) {
        $file 	= $e->getFile();
        $line 	= $e->getLine();
        $code 	= $e->getCode();
        $message= $e->getMessage();
        $func = strtolower($GLOBALS['logLevel'][$code]);
        $GLOBALS['log']->$func($file.$line.$message);
    });

    set_error_handler(function ($errno,$errstr,$errfile,$errline) {
        if (!(error_reporting() & $errno)) {
            return;
        }
        throw new ErrorException($errstr,$errno,0,$errfile,$errline);
    });

    register_shutdown_function(function () {
        $error = error_get_last();
        if (!$error) {
            return;
        }
        throw new ErrorException($error['message'],$error['type'],0,$error['file'],$error['line']);
    });
}

// 自动加载
//spl_autoload_register(__NAMESPACE__.'\yaecho\AutoLoader::loadprint');
spl_autoload_register([new AutoLoader, 'loadprint']);
// 框架核心入口
$yaecho = new Yaecho();
$yaecho->run();