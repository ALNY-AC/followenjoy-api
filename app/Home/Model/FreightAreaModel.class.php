<?php
namespace Home\Model;
use Think\Model;
class FreightAreaModel extends Model {
    
    public function _initialize (){}
    
    public function getList($freight_id){
        
        $where=[];
        $where['freight_id']=$freight_id;
        $list=$this
        ->where($where)
        ->cache(true,10)
        ->order('')
        ->select();
        $list=toTime($list);
        return $list;
        
    }
    
    
    
}