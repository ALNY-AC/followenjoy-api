<?php
namespace Admin\Model;
use Think\Model;
class HotLabelModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($add){
        $add['hot_label_id']=getMd5('hot_label');
        $add['add_time']=time();
        $add['edit_time']=time();
        
        if( $this->add($add)){
            return $this->where($add)->find();
        }else{
            return false;
        }
    }
    
    public function saveData($hot_label_id,$save){
        $where=[];
        $where['hot_label_id']=$hot_label_id;
        return $this->where($where)->save($save);
    }
    
    public function getList($data){
        $where  =   $data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->select();
        $list=toTime($list);
        return $list;
    }
    
    public function get($hot_label_id){
        $where=[];
        $where['hot_label_id']=$hot_label_id;
        return $this->where($where)->find();
    }
    
    public function del($ids){
        $where=[];
        $where['hot_label_id']=['in',$ids];
        return $this->where($where)->delete();
    }
    
    
}