<?php
namespace Admin\Model;
use Think\Model;
class MarketCouponModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($data){
        
        $data['market_coupon_id']=getMd5('MarketCoupon');
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $this->saveCouponGroup($data['market_coupon_id'],$data['coupon_group']);
        unset($data['coupon_group']);
        
        return $this->add($data);
        
    }
    
    public function saveData($id,$data){
        $where=[];
        $where['market_coupon_id']=$id;
        
        $this->saveCouponGroup($id,$data['coupon_group']);
        unset($data['coupon_group']);
        
        return $this->where($where)->save($data);
    }
    
    public function del($id){
        $where=[];
        $where['market_coupon_id']=['in',$id];
        return $this->where($where)->delete();
    }
    
    public function getList(){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=$this->bulider($list);
        
        return $list;
        
    }
    
    public function get($id){
        $where=[];
        $where['market_coupon_id']=$id;
        $data=$this->where($where)->find();
        $data['coupon_group']=$this->getCouponGroups($id);
        return $data;
    }
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        $list=$this->where($where)->select();
        $list=$this->bulider($list);
        return $list;
    }
    
    public function saveCouponGroup($id,$data){
        $MarketGroupBag=D('MarketGroupBag');
        $adds=[];
        
        // 先删除
        $where=[];
        $where['market_coupon_id']=$id;
        $MarketGroupBag->where($where)->delete();
        
        // 后添加
        foreach ($data as $k => $v) {
            $item=$v;
            $item['market_coupon_id']=$id;
            $adds[]=$item;
        }
        
        return  $MarketGroupBag->addAll($adds);
    }
    
    public function bulider($list){
        
        
        foreach ($list as $k => $v) {
            $v['coupon_group']=$this->getCouponGroups($v['market_coupon_id']);
            $list[$k]=$v;
        }
        $list=toTime($list);
        $list=toTime2($list,'Y-m-d',['end_at','start_at']);
        
        return $list;
    }
    
    
    
    public function getCouponGroups($id){
        $MarketGroupBag=D('MarketGroupBag');
        $CouponGroup=D('CouponGroup');
        
        $where=[];
        $where['market_coupon_id']=$id;
        
        $list=$MarketGroupBag->where($where)->select();
        
        foreach ($list as $k => $v) {
            $list[$k]=$CouponGroup->get($v['coupon_group_id']);
        }
        
        return $list;
    }
    
    
    
}