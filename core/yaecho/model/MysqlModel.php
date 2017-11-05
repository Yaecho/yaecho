<?php
/**
 * 基础模型
 */
namespace core\yaecho\model;

use core\yaecho\db\Mysqli;
use core\yaecho\help\ArrayHelper;
use core\yaecho\help\StringHelper;

class MysqlModel
{
    //数据库配置
    private $config = array();
    //数据库驱动保持
    private $db_driver = null;
    //表名
    protected $tableName = '';
    //查询条件
    protected $option = array();
    //sql各部分
    protected $sql_array = array();
    //sql语句
    protected $sql = '';
    //方法名
    private $methods = ['field', 'limit', 'where', 'order'];
    //错误信息
    public $error_info = '';

    /**
     * 获取数据单例，载入数据库配置，载入表名
     *
     * @param string $table 表名
     * @return void
     * @author Yaecho 2017-10-05 15:39:44
     */
    public function  __construct($table = '')
    {
        // 载入全局配置
        $this->config = $GLOBALS['config']['db'];
        // 获取数据库驱动
        $this->db_driver = Mysqli::getInstance($this->config);
        if (false === $this->db_driver) {
            $this->error_info = Mysqli::$error_info;
            return false;
        }
        // 载入表名
        $this->getTableName($table);
    }

    /**
     * 获取表名
     *
     * @param string $table 表名
     * @return void
     * @author Yaecho 2017-11-05 08:30:28
     */
    protected function getTableName($table = '')
    {
        if (empty($table)) {
            $table = explode('\\', static::class);
            $table = StringHelper::uncamelize(array_pop($table));
            // 删除_model
            $table = substr($table, 0, -6);
        }
        if (empty($this->tableName)) {
            $this->tableName = $table;
        }
    }

    /**
     * 魔术方法
     *
     * @param string $name 方法名
     * @param array $args 参数
     * @return object
     * @author Yaecho 2017-10-05 15:43:22
     */
    public function __call($name, $args)
    {
        //方法名转小写
        $name = strtolower($name);
        if (in_array($name, $this->methods, true)) {
            $this->option[$name] = reset($args);
        }
        return $this;
    }

    /**
     * 构造字段
     *
     * @return string
     * @author Yaecho 2017-10-05 16:16:54
     */
    protected function build_field()
    {
        if (!isset($this->option['field'])) {
            return '*';
        }
        if (true === $this->option['field']) {
            return '*';
        } 
        $field = array();
        foreach ($this->option['field'] as &$val) {
            // 过滤字符
            $field[] = $this->keyHandle($val);
        }
        
        return implode(',', $field);
    }

    /**
     * 构造表名
     *
     * @return string
     * @author Yaecho 2017-10-05 16:26:21 
     */
    protected function build_table()
    {
        // 加前缀
        if (empty($this->config['prefix'])) {
            $prefix = '';
        } else {
            $prefix = $this->config['prefix'] . '_';
        }
        
        return $this->keyHandle($prefix . $this->tableName);
    }

    /**
     * 构造where条件
     *
     * @return string
     * @author Yaecho 2017-10-07 07:36:23
     */
    protected function build_where()
    {
        if (!isset($this->option['where'])) {
            return '';
        }
        // 递归解析where
        return 'WHERE ' . $this->parse_where($this->option['where']);
    }

    /**
     * where解析递归函数
     *
     * @param array $data
     * @param string $operator 操作符
     * @return string
     * @author Yaecho 2017-10-07 13:50:10
     */
    private function parse_where($data, $operator = 'and')
    {
        $where = '';
        foreach ($data as $key => $value) {
            $key = strtolower($key);
            if (in_array($key, ['and', 'or'])) {
                if (empty($where)) {
                    $where = '(' . $this->parse_where($value, $key) . ')';
                } else {
                    $where .= $operator . '(' . $this->parse_where($value, $key) . ')';
                }
            } else {
                // 过滤字符
                $key = $this->escape($key);
                foreach ($value as $field => $val) {
                    // 过滤字符
                    $field = $this->keyHandle($field);
                    $val = $this->valueHandle($val);
                    if (empty($where)) {
                        $where = '(' . $field . $key . $val . ')';
                    } else {
                        $where .= $operator . '(' . $field . $key . $val . ')';
                    }
                }
            }
        }
        return $where;
    }

    /**
     * 构造排序条件
     *
     * @return string
     * @author Yaecho 2017-10-07 15:24:19
     */
    protected function build_order()
    {
        if (!isset($this->option['order'])) {
            return '';
        }
        $order = '';
        $lastKey = ArrayHelper::getLastKey($this->option['order']);
        foreach ($this->option['order'] as $field => $val) {
            // 字符过滤
            if (is_string($field)) {
                $order .= $this->keyHandle($field) . ' ' . $this->escape($val);
            } else {
                $order .= $this->keyHandle($val);
            }
            if ($field !== $lastKey) {
                $order .= ',';
            }
        }
        return 'ORDER BY ' . $order;
    }

    /**
     * 构造limit
     *
     * @return string
     * @author Yaecho 2017-10-07 15:47:41
     */
    protected function build_limit()
    {
        if (!isset($this->option['limit'])) {
            return '';
        }
        $limit = implode(',', $this->option['limit']);
        // 字符过滤
        $limit = $this->escape($limit);
        return 'LIMIT ' . $limit;
    }

    /**
     * 查询语句
     *
     * @param string $type sql类型 select update delete insert
     * @return string
     * @author Yaecho 
     */
    public function select()
    {
        $field = $this->build_field();
        $table = $this->build_table();
        $where = $this->build_where();
        $order = $this->build_order();
        $limit = $this->build_limit();

        $this->sql = trim(\sprintf('SELECT %s FROM %s %s %s %s', $field, $table, $where, $order, $limit));

        $result = $this->db_driver->query($this->sql);
        return $result;
    }

    /**
     * 统计
     *
     * @return void
     * @author Yaecho 
     * @todo 返回值
     */
    public function count()
    {
        $table = $this->build_table();
        $where = $this->build_where();

        $this->sql = trim(\sprintf('SELECT COUNT(1) AS num FROM %s %s LIMIT 1', $table, $where));

        $result = $this->db_driver->query($this->sql);
        if (is_array($result)) {
            $result = reset($result)['num'];
        }
        return $result;
    }

    /**
     * 新增
     *
     * @param array $data 新增数据
     * @return int|bool
     * @author Yaecho 2017-10-28 21:04:36
     */
    public function add(array $data)
    {
        if (!isset($this->option['field']) && !is_array($this->option['field'])) {
            return false;
        }
        $table = $this->build_table();
        $field = $this->build_field();
        $value = $this->build_value($data);
        if (false === $value) {
            return false;
        }
        $this->sql = \sprintf('INSERT INTO %s(%s) VALUES %s', $table, $field, $value);
        $result = $this->db_driver->execute($this->sql);
        if (false === $result) {
            return false;
        }
        return $result['last_id'];
    }

    /**
     * 构造value
     *
     * @param array $data 新增数据
     * @return array|bool
     * @author Yaecho 2017-10-28 21:12:44
     */
    protected function build_value(array $data)
    {
        // 一维数组转二维
        if (ArrayHelper::isOne($data)) {
            $data = array($data);
        }

        $value = '';
        $dataLastKey = ArrayHelper::getLastKey($data);
        $fieldLastKey = ArrayHelper::getLastKey($this->option['field']);

        foreach ($data as $rowKey => $row) {
            $value .= '(';
            foreach ($this->option['field'] as $fieldKey => $field) {
                if (!array_key_exists($field, $row)) {
                    return false;
                }
                $value .= $this->valueHandle($row[$field]);
                if ($fieldLastKey !== $fieldKey) {
                    $value .= ',';
                }
            }
            $value .= ')';
            if ($dataLastKey !== $rowKey) {
                $value .= ',';
            }
        }

        return $value;
    }

    /**
     * 更新数据
     *
     * @param array $data 数据
     * @return bool|int
     * @author Yaecho 2017-11-04 16:45:34
     */
    public function update(array $data)
    {
        if (!isset($this->option['field']) && !is_array($this->option['field'])) {
            return false;
        }
        $where = $this->build_where();
        $table = $this->build_table();
        $field = $this->build_field();
        $limit = $this->build_limit();
        $set = $this->build_set($data);
        if (false === $set) {
            return false;
        }

        $this->sql = trim(\sprintf('UPDATE %s SET %s %s %s', $table, $set, $where, $limit));
        
        $result = $this->db_driver->execute($this->sql);
        if (false === $result) {
            return false;
        }
        return $result['affected_rows'];
    }

    /**
     * 构造set
     *
     * @param array $data 数据
     * @return string
     * @author Yaecho 2017-11-04 16:30:06
     */
    protected function build_set(array $data)
    {
        $set = '';
        $lastKey = ArrayHelper::getLastKey($this->option['field']);
        foreach ($this->option['field'] as $key => $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }
            $set .= $this->keyHandle($field) . '=' . $this->valueHandle($data[$field]);
            if ($lastKey !== $key) {
                $set .= ',';
            }
        }
        return $set;
    }

    /**
     * 删除数据
     *
     * @return bool|int
     * @author Yaecho 2017-11-04 17:03:10
     */
    public function delete()
    {
        $where = $this->build_where();
        $table = $this->build_table();
        $limit = $this->build_limit();

        $this->sql = trim(\sprintf('DELETE FROM %s %s %s', $table, $where, $limit));

        $result = $this->db_driver->execute($this->sql);
        if (false === $result) {
            return false;
        }
        return $result['affected_rows'];
    }

    /**
     * 字符过滤
     *
     * @param mix $str 需要过滤的字符
     * @return string
     * @author Yaecho 2017-11-04 21:22:00
     */
    private function escape($str)
    {
        return $this->db_driver->getMysqli()->real_escape_string($str);
    }

    /**
     * 表名、字段名称过滤
     *
     * @param mix $key 字段名称
     * @return string
     * @author Yaecho 2017-11-04 21:24:47 
     */
    private function keyHandle($key)
    {
        return '`' . $this->escape($key) . '`';
    }

    /**
     * 值过滤
     *
     * @param mix $value 值
     * @return string
     * @author Yaecho 2017-11-04 21:54:25 
     */
    private function valueHandle($value)
    {
        return "'" . $this->escape($value) . "'";
    }
}