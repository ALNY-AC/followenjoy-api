<?php
namespace Admin\Model;
use Think\Model;
class FreightAreaModel extends Model {
    
    public function _initialize (){}
    
    public function getList($freight_id){
        
        $where=[];
        $where['freight_id']=$freight_id;
        $list=$this
        ->where($where)
        ->order('')
        ->select();
        $list=toTime($list);
        return $list;
        
    }
    
    
    public function creat($areas,$freight_id){
        
        $where=[];
        $where['freight_id']=$freight_id;
        // ===================================================================================
        // 先删除
        $this->where($where)->delete();
        
        // ===================================================================================
        // 构造基本数据
        foreach ($areas as $key => $area) {
            $freight_area_id=getmd5('freight_area');
            
            $add['freight_area_id']=$freight_area_id;
            $add['freight_id']=$freight_id;
            
            $add['area']=$area['area'];
            $add['first']=$area['first'];
            $add['first_price']=$area['first_price'];
            $add['continued']=$area['continued'];
            $add['continued_price']=$area['continued_price'];
            
            $add['add_time']=time();
            $add['edit_time']=time();
            
            $areas[$key]=$add;
        }
        // ===================================================================================
        // 添加到数据库
        
        $result=$this->addAll($areas);
        
        
        // ===================================================================================
        // 返回数据
        
        return $result;
        
    }
    
    
    
}