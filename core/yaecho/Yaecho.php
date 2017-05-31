<?php
namespace core\yaecho;

class Yaecho {
    public function run(){
        echo "框架核心入口";
        echo YAECHO_PATH.'/runtime/log/'.date('Y-m-d', time()).'.log';
    }
}