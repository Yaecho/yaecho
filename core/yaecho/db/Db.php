<?php
/**
 * 数据库接口
 */
namespace core\yaecho\db;

interface Db 
{
    /**
     * 查询方法
     *
     * @param string $sql 查询sql语句
     * @return mixed
     * @author Yaecho 2017-10-03 14:26:42
     */
    public function query(string $sql);

    /**
     * 执行方法
     *
     * @param string $sql
     * @return mixed
     * @author Yaecho 2017-10-03 14:27:35
     */
    public function execute(string $sql);

    /**
     * 获取单例方法
     *
     * @param array $config 配置
     * @return object
     * @author Yaecho 2017-10-03 14:42:03
     */
    public static function getInstance($config = array());
}