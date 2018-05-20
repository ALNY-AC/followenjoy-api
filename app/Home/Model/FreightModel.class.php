<?php
namespace Home\Model;
use Think\Model;
class FreightModel extends Model {
    
    public function _initialize (){}
    
    public function getList($data){
        
        // ===================================================================================
        // 创建模型
        $FreightArea=D('FreightArea');
        
        
        
        // ===================================================================================
        // 取得数据
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $list=toTime($list);
        // ===================================================================================
        // 循环取得配送区域
        
        foreach ($list as $key => $freight) {
            $freight_id=$freight['freight_id'];
            $areas=$FreightArea->getList($freight_id);
            $freight['areas']=$areas;
            $list[$key]=$freight;
        }
        
        return $list;
        
    }
    public function getAll(){
        
        // ===================================================================================
        // 创建模型
        $FreightArea=D('FreightArea');
        
        // ===================================================================================
        // 取得数据
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->select();
        $list=toTime($list);
        // ===================================================================================
        // 循环取得配送区域
        
        foreach ($list as $key => $freight) {
            $freight_id=$freight['freight_id'];
            $areas=$FreightArea->getList($freight_id);
            $freight['areas']=$areas;
            $list[$key]=$freight;
        }
        
        return $list;
        
    }
    public function get($freight_id){
        // ===================================================================================
        // 创建模型
        $FreightArea=D('FreightArea');
        
        // ===================================================================================
        // 取得基本数据
        
        $where=[];
        $where['freight_id']=$freight_id;
        $freight=$this->where($where)->find();
        
        // ===================================================================================
        // 找到区域数据
        
        $freight_id=$freight['freight_id'];
        $areas=$FreightArea->getList($freight_id);
        $freight['areas']=$areas;
        
        return $freight;
    }
    
    
    
}