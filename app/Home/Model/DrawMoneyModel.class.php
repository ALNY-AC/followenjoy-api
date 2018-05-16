<?php
namespace Home\Model;
use Think\Model;
class DrawMoneyModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($add){
        
        $add['draw_money_id']=date('YmdHis',time()).rand(10000,99999);
        $add['user_id']=session('user_id');
        $add['add_time']=time();
        $add['edit_time']=time();
        
        return $this->add($add);
        
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $where['user_id']=session('user_id');
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=toTime($list);
        
        return $list;
        
    }
    
    public function get($draw_money_id){
        $where=[];
        $where['draw_money_id']=$draw_money_id;
        $where['user_id']=session('user_id');
        $result=$this->where($where)->find();
        $result=toTime([$result])[0];
        return $result;
    }
    
    public function getAll($data){
        $where=$data['where'];
        $where['user_id']=session('user_id');
        return $this->order('add_ime desc')->where($where)->select();
    }
    
}