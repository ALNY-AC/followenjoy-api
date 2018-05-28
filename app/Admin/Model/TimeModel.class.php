<?php
namespace Admin\Model;
use Think\Model;
class TimeModel extends Model {
    
    
    public function _initialize (){}
    
    public function create($data){
        $time_id=getMd5('time_id');
        $data['time_id']=$time_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        if($this->add($data)){
            return $time_id;
        }else{
            return false;
        }
    }
    
    public function get($time_id){
        $where=[];
        $where['time_id']=$time_id;
        return $this->where($where)->find();
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        if(!$field){
            $field=[
            'time_id',
            'time_name',
            'start_time',
            'end_time',
            'is_all_goods',
            'type',
            'discounted',
            'add_time',
            // 'edit_time',
            ];
        }
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=$this->bulider($list);
        
        return $list;
    }
    
    public function getAll(){
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        if(!$field){
            $field=[
            'time_id',
            'time_name',
            'start_time',
            'end_time',
            'is_all_goods',
            'type',
            'discounted',
            'add_time',
            // 'edit_time',
            ];
        }
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->field($field)
        ->select();
        
        $list=$this->bulider();
        
        return $list;
    }
    
    public function saveData($time_id,$data){
        $where=[];
        $where['time_id']=$time_id;
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function del($ids){
        $where=[];
        $where['time_id']=['in',getIds($ids)];
        $TimeGoods=D('TimeGoods');
        return $this->where($where)->delete()!==false &&  $TimeGoods->where($where)->delete()!==false;
    }
    
    public function bulider($list){
        foreach ($list as $k => $v) {
        }
        $list=toTime($list);
        return $list;
    }
}