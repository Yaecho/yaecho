<?php

namespace app\controller;

use core\yaecho\Controller;
use app\model\NoteModel;

class Note extends Controller
{
    public function actionGet()
    {
        $model = new NoteModel();
        $db = $model->getDb();
        $result = $db->prepare("select `id`,`title`,`update_time`,`add_time` from `note` where `is_delete` = 0 limit ? OFFSET ?");
        $result->bind_param('dd', $limit, $start);
        $limit = $this->request('post', 'per_page', 10);
        $start = $this->request('post', 'page', 0) - 1;
        $result->execute();
        $result->bind_result($id, $title, $update_time, $add_time); 
        $data = array();
        while($result->fetch()){ 
            $data[] = array(
                'id' => $id,
                'title' => $title,
                'update_time' => $update_time,
                'add_time' => $add_time,
            );
        } 
        
        $count = $db->query("select count(1) as `count` from `note` where `is_delete` = 0");
        $count->data_seek(0); //重置指针到起始
        $count =  $count->fetch_assoc()['count'];
        return $this->response(['count'=>$count,'list'=>$data]);
    }

    public function actionAdd()
    {
        $title = $this->request('post', 'title');
        $text = $this->request('post', 'text');
        if (false !== $title and false !== $text) {
            $model = new NoteModel();
            $db = $model->getDb();
            $res = $db->query("insert into `note` (`title`,`text`, `add_time`, `update_time`, `add_uid`) values ('$title', '$text', '1', 1, 1)");
            if ($res) {
                $result = 'success';
            } else {
                return $this->response(array(), '数据库错误', true);
            }
        } else {
            return $this->response(array(), '表单数据不完整', true);
        }

        return $this->response($result);
    }
}