<?php
namespace Admin\Model;
use Think\Model;
class SkuGroupModel extends Model {
    public function _initialize (){}
    
    public function getList($data){
        
        $SkuGroupProp=D('SkuGroupProp');
        
        $skuGroups=$this
        ->where($data['where'])
        ->order('add_time asc')
        ->select();
        
        //找规格项
        foreach ($skuGroups as $key => $group) {
            $sku_group_id=$group['sku_group_id'];
            $group['node']= $SkuGroupProp->getList($sku_group_id);
            $skuGroups[$key]=$group;
        }
        return $skuGroups;
    }
    
    public function get($sku_group_id){
        
        $SkuGroupProp=D('SkuGroupProp');
        
        $where=[];
        $where['sku_group_id']=$sku_group_id;
        
        $group=$this
        ->where($where)
        ->find();
        
        $group['node']= $SkuGroupProp->getList($sku_group_id);
        return $group;
        
    }
    
}