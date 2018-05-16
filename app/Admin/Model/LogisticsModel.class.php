<?php
namespace Admin\Model;
use Think\Model;
class LogisticsModel extends Model {
    
    
    public function _initialize (){}
    
    
    public function saveData($order_id,$save){
        $where=[];
        $where['order_id']=$order_id;
        $save['edit_time']=time();
        
        if($save['logistics_number']){
            $Order=D('Order');
            $order=$Order->where($where)->find();
            
            if($order['state']=='2'){
                $oSave=[];
                $oSave['state']=3;
                $Order->where($where)->save($oSave);
            }
            
        }
        
        return $this->where($where)->save($save);
    }
    
}