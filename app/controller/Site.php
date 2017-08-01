<?php
namespace app\controller;  

use core\yaecho\Controller;
use core\yaecho\BaseModel;

class Site extends Controller
{
    public function actionIndex()
    {
        $m = new duwehfuwef();
        $model = new BaseModel();
        $res = $model->select('img');
        $data = $res->all();
        var_dump($data);
        echo $_GET['wwew'];
        return $this->render('index', ['data'=>123]);
    }
    public function actionMySecondFun()
    {
        return 'my second fun';
    }
}