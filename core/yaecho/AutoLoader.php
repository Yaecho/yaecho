<?php
namespace core\yaecho;

/*
* 自动加载类
*/
class AutoLoader {

    public static function loadprint($class){
        $file = YAECHO_PATH."/".$class.".php";
        // "Linux需要转义斜杠";
        $file = str_replace('\\','/',$file);
        if (is_file($file)) {
            include($file);
        }
    }

}