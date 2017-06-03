<?php
namespace app\controller;  

use core\yaecho\Controller;

class Site extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', ['data'=>123]);
    }
    public function actionMySecondFun()
    {
        return 'my second fun';
    }
}