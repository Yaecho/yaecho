<?php
namespace core;

require_once YAECHO_PATH.'/core/yaecho/AutoLoader.php';

use core\yaecho\AutoLoader;
use core\yaecho\Yaecho;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Noodlehaus\Config;

// Config
$GLOBALS['config'] = new Config(YAECHO_PATH. '/app/config/conf.php');

//logger
$log = new Logger('system');
$log->pushHandler(new StreamHandler(YAECHO_PATH.'/runtime/log/'.date('Y-m-d', time()).'.log', Logger::DEBUG));

// 日志消息等级
$logLevel = array(
    E_ERROR             => 'CRITICAL',
    E_WARNING           => 'WARNING',
    E_PARSE             => 'ALERT',
    E_NOTICE            => 'NOTICE',
    E_CORE_ERROR        => 'CRITICAL',
    E_CORE_WARNING      => 'WARNING',
    E_COMPILE_ERROR     => 'ALERT',
    E_COMPILE_WARNING   => 'WARNING',
    E_USER_ERROR        => 'ERROR',
    E_USER_WARNING      => 'WARNING',
    E_USER_NOTICE       => 'NOTICE',
    E_STRICT            => 'NOTICE',
    E_RECOVERABLE_ERROR => 'ERROR',
    E_DEPRECATED        => 'NOTICE',
    E_USER_DEPRECATED   => 'NOTICE',
);

//日志记录匿名函数
$logWrite = function ($exception, $inspector, $run) use ($logLevel, $log) {
    $file 	= $exception->getFile();
    $line 	= $exception->getLine();
    $code 	= $exception->getCode();
    $message= $exception->getMessage();
    if (empty($logLevel[$code])) {
        $code = E_WARNING;
    }
    $func = strtolower($logLevel[$code]);
    $log->$func($file . ' ' . $line . ' ' . $message);
};

//Whoops
if ($GLOBALS['config']->get('debug')) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->pushHandler($logWrite);
    $whoops->register();
} else {
    set_exception_handler(function ($e) {
        $logWrite($e, null, null);
    });
    
    set_error_handler(function ($errno,$errstr,$errfile,$errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        $func = strtolower($GLOBALS['logLevel'][$errno]);
        $GLOBALS['log']->$func($errfile.$errline.$errstr);
    });
    
    register_shutdown_function(function () {
        $error = error_get_last();
        if (!$error) {
            return false;
        }
        $func = strtolower($GLOBALS['logLevel'][$error['type']]);
        $GLOBALS['log']->$func($error['file'].$error['line'].$error['message']);
    });    
}

// 自动加载
//spl_autoload_register(__NAMESPACE__.'\yaecho\AutoLoader::loadprint');
spl_autoload_register([new AutoLoader, 'loadprint']);
// 框架核心入口
$yaecho = new Yaecho();
$yaecho->run();