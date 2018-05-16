<?php
namespace Admin\Model;
use Think\Model;
class AfterSaleModel extends Model {
    
    
    public function _initialize (){}
    
    public function saveData($after_sale_id,$save,$order_id){
        
        $Order=D('Order');
        $where=[];
        $where['order_id']=$order_id;
        if($save['state']==1 || $save['state']==0){
            $OrderSave['state']=5;
            $OrderSave['edit_time']=time();
        }
        if($save['state']==2){
            $OrderSave['state']=7;
            $OrderSave['edit_time']=time();
        }
        if($save['state']==3){
            $OrderSave['state']=8;
            $OrderSave['edit_time']=time();
        }
        
        $Order->where($where)->save($OrderSave);
        
        $where=[];
        $where['after_sale_id']=$after_sale_id;
        $save['edit_time']=time();
        return $this->where($where)->save($save);
        
    }
    
    
}