<?php
/**
 * 字符串助手类
 */
namespace core\yaecho\help;

class StringHelper
{
    /**
    * 下划线转驼峰
    */
    public static function camelize($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = strtolower($uncamelized_words);
        return trim(str_replace($separator, "", ucwords($uncamelized_words, $separator)));
    }

    /**
    * 驼峰命名转下划线命名
    * 思路:
    * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
    */
    public static function uncamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}
