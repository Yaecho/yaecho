<?php

namespace app\controller;  

use core\yaecho\Controller;
use app\model\NoteModel;

class Site extends Controller
{
    public function actionIndex()
    {
        $model = new NoteModel();
        $result = $model->field(true)->limit([2,3])->select();
        var_dump($result);
        //return $this->response(['list'=>$result]);
    }

    public function actionTest()
    {
       
    }
}