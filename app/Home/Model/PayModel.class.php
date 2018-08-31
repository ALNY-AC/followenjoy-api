<?php
namespace Home\Model;
use Think\Model;
class PayModel extends Model {
    
    
    public function _initialize (){}
    
    public function setState($pay_id,$state){
        $where=[];
        $where['pay_id']=$pay_id;
        $Order=D('Order');
        $orderSave=[];
        $paySave=[];
        
        // 1、待付款
        // 2、待发货
        // 3、待收货
        // 4、交易成功
        // 5、退款/退货
        // 6、已关闭
        // 7、已退款
        // 8、退款失败
        $paySave['state']=$state;
        
        if($state==1){
            // 支付成功
            $orderSave['state']=2;
        }
        
        $orderSave['edit_time']=time();
        $paySave['edit_time']=time();
        
        
        $is1= $Order->where($where)->save($orderSave);
        $is2= $this->where($where)->save($paySave);
        
        return $is1 && $is2;
    }
    
    public function setPayType($pay_id,$pay_type){
        $Pay=D('Pay');
        $data=[];
        $data['pay_type']=$pay_type;
        $where=[];
        $where['pay_id']=$pay_id;
        
        $Pay->where($where)->save($data);
        
    }
    
    public function getList(){
        
    }
    
    
    
    
    
}