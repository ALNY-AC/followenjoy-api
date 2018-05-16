<?php
namespace Admin\Model;
use Think\Model;
class PowerNumModel extends Model {
    
    public function _initialize (){}
    
    public function creat($data){
        
        $data['power_num_id']=getMd5('power_num');
        $data['add_time']=time();
        $data['edit_time']=time();
        
        return $this->add($data);
        
    }
    public function get($power_num_id){
        $where=[];
        $where['power_num_id']=$power_num_id;
        return $this->where($where)->find();
    }
    public function saveData($power_num_id,$data){
        $where=[];
        $where['power_num_id']=$power_num_id;
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function del($ids){
        $where=[];
        $where['power_num_id']=['in',$ids];
        return $this->where($where)->delete();
    }
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        $list=$this
        ->order('power_num asc')
        ->where($where)
        ->select();
        return $list;
    }
}