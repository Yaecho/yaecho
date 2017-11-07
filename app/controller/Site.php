<?php

namespace app\controller;  

use core\yaecho\Controller;
use app\model\NoteModel;
use core\yaecho\help\Cookie;

class Site extends Controller
{
    public function actionIndex()
    {
        $cookie = new Cookie();
        $cookie->setName('test')->setValue('qwert')->setTime('+ 1 day')->create();
        var_dump($cookie->get());
    }

    public function actionTest()
    {
       
    }
}