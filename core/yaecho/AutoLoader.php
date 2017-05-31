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
        echo $file."<br>";    //这句话是为了调试使用
        if (is_file($file)) {
            include($file);
        }
    }

}