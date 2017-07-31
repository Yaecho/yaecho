<?php
namespace app\controller;  

use core\yaecho\Controller;
use core\yaecho\BaseModel;

class Site extends Controller
{
    public function actionIndex()
    {
        $model = new BaseModel();
        $res = $model->select('img');
        $data = $res->all();
        var_dump($data);
        echo 2/0;
        echo $_GET['wwew'];
        return $this->render('index', ['data'=>123]);
    }
    public function actionMySecondFun()
    {
        return 'my second fun';
    }
}