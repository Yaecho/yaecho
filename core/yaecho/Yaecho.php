<?php
namespace core\yaecho;

use Noodlehaus\Config;

class Yaecho {
    private $controller; //默认控制器
    private $action; //默认方法
    private $config; //配置文件

    public function __construct()
    {
        $this->config = new Config(YAECHO_PATH.'/config/conf.php');
    }

    public function run(){
        $this->route();
        $class = "\\app\\controller\\".ucfirst($this->controller);
        $obj = new $class();
        $action = $this->action;
        $obj->$action();
    }

    // 路由处理
    public function route()
    {
        $param = $_GET;
        
        $r = isset($param['r'])?$param['r']:NULL;
        $r = explode('/', $r);

        $this->controller = !empty($r[0])?$r[0]:$this->config['defaultController'];
        $this->action = !empty($r[1])?$r[1]:$this->config['defaultAction'];            
    }
}