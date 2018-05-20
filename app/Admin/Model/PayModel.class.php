<?php
namespace Admin\Model;
use Think\Model;
class PayModel extends Model {
    
    
    public function _initialize (){}
    
    public function saveData($pay_id,$save){
        $where=[];
        $where['pay_id']=$pay_id;
        $this->where($where)->save($save);
    }
    
    
    
    
}