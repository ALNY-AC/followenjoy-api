<?php
namespace Admin\Model;
use Think\Model;
class CouponModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $User=D('User');
        //找用户信息
        for ($i=0; $i <count($list) ; $i++) {
            if($list[$i]['user_id']){
                $user_id=$list[$i]['user_id'];
                $where=[];
                $where['user_id']=$user_id;
                $userInfo=  $User->where($where)->find();
                $list[$i]['userInfo']=$userInfo;
            }else{
                $list[$i]['userInfo']=null;
            }
        }
        $list=toTime($list);
        $list=toTime2($list,'Y-m-d',['end_at','start_at']);
        
        return $list;
    }
    
    //获得一个
    public function get($goods_id){
        
        return $goods;
    }
    
    public function groupToCode($coupon_group_id,$count){
        $CouponGroup=D('CouponGroup');
        
        $data=[];
        $where=[];
        $where['coupon_group_id']=$coupon_group_id;
        $group=$CouponGroup->where($where)->find();
        
        for ($i=1; $i <= $count; $i++) {
            
            $item=[];
            $item['coupon_id']=getMd5('coupon'.$i);
            $item['coupon_group_id']=$coupon_group_id;
            $item['user_id']='';
            $item['class_id']=$group['class_id'];
            $item['name']=$group['coupon_group_name'];
            $item['discount']=$group['discount'];
            $item['denominations']=$group['denominations'];
            $item['origin_condition']=$group['origin_condition'];
            $item['start_at']=$group['start_at'];
            $item['end_at']=$group['end_at'];
            $item['state']=1;
            $item['add_time']=time();
            $item['edit_time']=time();
            
            $data[]=$item;
            
        }
        
        
        return   $this->addAll($data);
        
    }
    
}