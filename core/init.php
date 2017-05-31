<?php
namespace core;

require_once YAECHO_PATH.'/core/yaecho/AutoLoader.php';

use core\yaecho\AutoLoader;
use core\yaecho\Yaecho;
// 自动加载
spl_autoload_register([new AutoLoader(),'loadprint']);
// 框架核心入口
$momoma = new Yaecho();
$momoma->run();