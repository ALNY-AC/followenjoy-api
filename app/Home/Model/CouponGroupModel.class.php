<?php
namespace Home\Model;
use Think\Model;
class CouponGroupModel extends Model {
    
    
    public function _initialize (){}
    
    
    
    public function getList(){
        
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
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        return $this->where($where)->select();
    }
    
    
    
}