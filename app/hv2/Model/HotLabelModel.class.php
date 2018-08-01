<?php
namespace Home\Model;
use Think\Model;
class HotLabelModel extends Model {
    
    
    public function _initialize (){}
    
    
    
    public function getList($data){
        $where  =   $data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->select();
        $list=toTime($list);
        return $list;
    }
    
    
    
    
}