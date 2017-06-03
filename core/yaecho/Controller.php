<?php
namespace core\yaecho;

use Noodlehaus\Config;
use League\Plates\Engine;

class Controller 
{
    private $config; //配置
    private $templates; //模板

    public function __construct()
    {
        $this->config = new Config(YAECHO_PATH.'/config/conf.php');
        $this->templates = new Engine(YAECHO_PATH.'/'.$this->config['appPath'].'/'.$this->config['templatePath']);
    }

    public function render($viewName, array $data)
    {
        return $this->templates->render($viewName, $data);
    }
}