<?php
namespace Admin\Model;
use Think\Model;
class CouponGroupModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($data){
        $data['coupon_group_id']=getMd5('coupon_group');
        $data['add_time']=time();
        $data['edit_time']=time();
        return $this->add($data);
    }
    
    public function saveData($id,$data){
        $where=[];
        $where['coupon_group_id']=$id;
        $data['edit_time']=time();
        unset($data['add_time']);
        return $this->where($where)->save($data);
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $list=toTime($list);
        $list=toTime2($list,'Y-m-d',['end_at','start_at']);
        
        return $list;
    }
    
    public function get($id){
        $where=[];
        $where['coupon_group_id']=$id;
        return $this->where($where)->find($save);
    }
    
    public function del($id){
        $where=[];
        $where['coupon_group_id']=['in',$id];
        return $this->where($where)->delete();
    }
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        return $this->where($where)->select();
    }
    
    
    
}