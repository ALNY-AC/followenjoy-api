<?php
namespace Common\Model;
use Think\Model;
class PayLogModel extends Model {
    
    
    public function creat($data){
        
        if(!$data['user_id']){
            return false;
        }
        
        $pay_log_id=getMd5('pay_log');
        
        $data['pay_log_id']=$pay_log_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $is=$this->add($data);
        
        if($is){
            return $pay_log_id;
        }else{
            return false;
        }
        
    }
    
    public function get($pay_log_id){
        $where['pay_log_id']=$pay_log_id;
        $data=$this->where($where)->field();
        $data=toTime([$data])[0];
        return $data;
    }
    
    
    public function getAll($data){
        $where=$data['where']?$data['where']:[];
        $list=$this->where($where)->select();
        $list=toTime($list);
        return $list;
    }
    
    public function getList($data){
        $page=$data['page']?$data['page']:1;
        $limit=$data['limit']?$data['limit']:10;
        $where=$data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $list=toTime($list);
        return $list;
    }
    
    public function del($pay_log_id){
        $where['pay_log_id']=['in',getIds($pay_log_id)];
        return $this->where($where)->delete();
    }
    
    public function saveData($pay_log_id,$data){
        $where['pay_log_id']=['in',getIds($pay_log_id)];
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function test($echo){
        
        if($echo){
            echo 'PayLogModel';
        }else{
            return "PayLogModel";
        }
    }
    
    
    
}