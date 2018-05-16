<?php
namespace Admin\Model;
use Think\Model;
class SkuGroupPropModel extends Model {
    public function _initialize (){}
    
    public function getList($sku_group_id){
        
        $where=[];
        $where['sku_group_id']=$sku_group_id;
        
        $props=$this
        ->where($where)
        ->order('add_time asc')
        ->select();
        
        return $props;
    }
    
}