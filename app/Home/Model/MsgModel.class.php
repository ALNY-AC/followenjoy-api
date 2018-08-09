<?php
namespace Home\Model;
use Think\Model;
class MsgModel extends Model {
    
    
    public function getList($data){
        $where=$data['where'];
        $msgs=$this
        ->order('add_time desc')
        ->field(
            [
            'msg_id',
            'msg_head',
            'msg_title',
            'msg_info',
            'is_up',
            'type',
            'add_time',
            'edit_time'
            ]
        )
        ->where($where)
        ->select();
        $msgs=toTime($msgs);
        return $msgs;
    }
    
    public function get($msg_id){
        $where=[];
        $where['msg_id']=$msg_id;
        $data=$this->where($where)->find();
        
        $data=toTime([$data])[0];
        
        return $data;
    }
    
    
}