<?php
namespace Admin\Model;
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
    public function saveDate($freight_id,$save){
        // ===================================================================================
        // 创建模型
        $FreightArea=D('FreightArea');
        
        // ===================================================================================
        // 创建条件
        $where=[];
        $where['freight_id']=$freight_id;
        
        // ===================================================================================
        // 先保存组的数据
        $freightData=$save;
        //修改一些数据
        unset($freightData['freight_id']);
        unset($freightData['add_time']);
        unset($freightData['areas']);
        $freightData['edit_time']=time();
        
        // ===================================================================================
        // 保存到数据库中
        $isFreightDataSaveTrue=$this->where($where)->save($freightData);
        
        // ===================================================================================
        // 调用区域模型的方法，保存区域数据，传入第二个参数代表先删除后保存。
        if($save['areas'] ){
            $isAreaSaveTrue=$FreightArea->creat($save['areas'],$freight_id);
        }else{
            $isAreaSaveTrue=true;
        }
        
        // ===================================================================================
        // 返回数据
        return $isFreightDataSaveTrue && $isAreaSaveTrue;
        
    }
    public function del($ids){
        
        // ===================================================================================
        // 创建模型
        $FreightArea=D('FreightArea');
        
        // ===================================================================================
        // 创建条件
        $where=[];
        $where['freight_id']=['in',$ids];
        
        
        // ===================================================================================
        // 先删除基本数据
        $isDelTrue=$this->where($where)->delete();
        
        // ===================================================================================
        // 再删除区域数据
        $isDelAreaTrue=$FreightArea->where($where)->delete();
        
        
        return $isDelTrue && $isDelAreaTrue!==false;
        
    }
    public function creat($add){
        // ===================================================================================
        // 创建模型
        $FreightArea=D('FreightArea');
        
        // ===================================================================================
        // 构造数据
        $freight_id=getMd5('freight');
        $areas=$add['areas'];
        unset($add['areas']);
        unset($add['areasInfo']);
        $add['freight_id']=$freight_id;
        $add['add_time']=time();
        $add['edit_time']=time();
        
        
        // ===================================================================================
        // 插入到数据
        $isAddTrue=$this->add($add);
        
        // ===================================================================================
        // 调用区域的创建函数来创建区域，传入第二个参数以便知道这个区域是谁的
        if(count($areas)>0){
            $isAddFreightAreaTrue= $FreightArea->creat($areas,$freight_id);
        }else{
            $isAddFreightAreaTrue=true;
        }
        
        return $isAddTrue && $isAddFreightAreaTrue;
    }
    
    
}