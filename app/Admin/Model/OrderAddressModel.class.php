<?php
namespace Admin\Model;
use Think\Model;
class OrderAddressModel extends Model {
    
    
    public function _initialize (){}
    
    public function get($address_id){
        
        $where=[];
        $where['address_id']=$address_id;
        
        return $this->where($where)->find();
        
        
    }
    
}