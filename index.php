<?php 
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Noodlehaus\Config;

define("YAECHO_PATH", dirname(__FILE__)); //项目根目录

// Config
$config = new Config(YAECHO_PATH. '/config/conf.php');

// Whoops
if($config->get('debug')) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

//logger
$log = new Logger('yaecho');
$log->pushHandler(new StreamHandler(YAECHO_PATH.'/runtime/log/'.date('Y-m-d', time()).'.log', Logger::DEBUG));

//$log->warning('Foo');
//$log->error('Bar');


require_once YAECHO_PATH . '/core/init.php'; //引入框架核心文件