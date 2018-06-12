<?php
namespace Common\Model;
use Think\Model;
class LaissezPasserModel extends Model {
    
    public function create($data){
        $data['laissez_passer_id']=getMd5('laissez_passer');
        $data['add_time']=time();
        $data['edit_time']=time();
        $this->add($data);
        return $this->get($data['laissez_passer_id']);
    }
    
    public function get($id){
        $where=[];
        $where['laissez_passer_id']=$id;
        return $this->where($where)->find();
    }
    
    public function getList($data){
        
        $where=$data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time asc')
        ->where($where)
        ->select();
        $list=toTime($list);
        return $list;
        
    }
    
    public function del($id){
        $where=[];
        $where['laissez_passer_id']=['in',getIds($id)];
        return $this->where($where)->delete();
    }
    
    public function saveData($id,$data){
        $where=[];
        $where['laissez_passer_id']=['in',getIds($id)];
        $data['edit_time']=time();
        unset($data['add_time']);
        return $this->where($where)->save($data);
    }
    
    // 验证手机号是否免签
    public function validate($user_id){
        
        $where=[];
        $where['user_id']=$user_id;
        $is= $this->where($where)->find();
        
        if($is){
            return true;
        }else{
            return false;
        }
        
    }
    
}