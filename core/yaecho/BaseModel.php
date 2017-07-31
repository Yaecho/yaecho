<?php
namespace core\yaecho;

use Noodlehaus\Config;

class BaseModel 
{
    private $config;
    private $db;
    private $sql;
    private $temp;

    public function  __construct()
    {
        $this->config = $GLOBALS['config'];
        $this->db = new \PDO(
            sprintf(
                '%s:host=%s;dbname=%s;port=%s;charset=%s',
                $this->config['dbType'],
                $this->config['host'],
                $this->config['dbName'],
                $this->config['port'],
                $this->config['charset']
            ),
            $this->config['username'],
            $this->config['password']
        );
    }

    public function select($table)
    {
        $this->sql = 'select * from '.$table.' limit 10';
        $this->temp = $this->db->prepare($this->sql);

        return $this;
    }

    public function all()
    {
        $this->temp->execute();
        $result = $this->temp->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}