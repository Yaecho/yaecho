<?php
/**
 * 数组助手类
 */
namespace core\yaecho\help;

class ArrayHelper 
{
    /**
     * 判断是否为一维数组
     *
     * @param array $data 数组
     * @return bool
     * @author Yaecho 2017-10-28 16:11:54
     */
    public static function isOne(array $data)
    {
        return count($data) === count($data, COUNT_RECURSIVE);
    }

    /**
     * 返回数组最后一个元素的键名
     *
     * @param array $data 数组
     * @return mix
     * @author Yaecho 2017-10-28 16:38:33
     */
    public static function getLastKey(array $data)
    {
        end($data);
        return key($data);
    }
}
