<?php 
require 'vendor/autoload.php';

define("MOMOMA_PATH", dirname(__FILE__)); //项目根目录
// Config
$conf = new \Noodlehaus\Config(MOMOMA_PATH. '/config/conf.php');
//echo $conf->get('debug');
//echo $conf['debug'];

// Whoops
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

echo 123;