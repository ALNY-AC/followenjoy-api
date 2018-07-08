<?php
namespace Home\Model;
use Think\Model;
class AfterSaleModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($add){
        
        $add['after_sale_id']=getMd5('AfterSale');
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['user_id']=session('user_id');
        
        $imgList=$add['imgList'];
        unset($add['imgList']);
        
        $arr=[];
        foreach ($imgList as $key => $value) {
            $item=[];
            $item['after_sale_id']=$add['after_sale_id'];
            $item['src']=$value;
            $arr[]=$item;
        }
        
        $AfterSaleImg=D('AfterSaleImg');
        $AfterSaleImg->addAll($arr);
        
        //设置订单状态
        $Order=D('Order');
        $where=[];
        $where['order_id']=$add['order_id'];
        $save=[];
        $save['state']=5;
        $Order->where($where)->save($save);
        
        return $this->add($add);
        
    }
    
    public function saveData($order_id,$save){
        $where=[];
        $where['order_id']=$order_id;
        $where['user_id']=session('user_id');
        $save['edit_time']=time();
        return   $this->where($where)->save($save);
        
    }
    
    public function get($after_sale_id,$name='after_sale_id'){
        $where=[];
        $where[$name]=$after_sale_id;
        return $this->where($where)->find();
    }
    
    
}