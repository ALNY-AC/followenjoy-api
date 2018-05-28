<?php
namespace Home\Model;
use Think\Model;
class TimeGoodsModel extends Model {
    
    
    public function _initialize (){}
    
    
    public function getList($data){
        $time=$data['time'];
        
        $start_time=strtotime($time);
        
        $where=[];
        $where['start_time']=['EQ',$start_time];
        $Time=D("Time");
        
        $time=$Time->where($where)->find();
        
        if(!$time){
            return [];
        }
        
        $time_id=$time['time_id'];
        
        $where=[];
        $where['time_id']=$time_id;
        
        $Goods=D('Goods');
        $goods_id=$this->where(['time_id'=>$time_id])->getField('goods_id',true);
        
        if($goods_id){
            $where=[];
            $where['goods_id']=['in',$goods_id];
        }else{
            $list=[];
            return [];
        }
        
        $list=$Goods->getList($data,$where);
        return $list;
    }
    
    public function getAll($data){
        
        
        $Goods=D('Goods');
        $time_id=$data['time_id'];
        
        $goods_id=$this->where(['time_id'=>$time_id])->getField('goods_id',true);
        if($goods_id){
            
            $data['where']['goods_id']=$goods_id;
            $list=$Goods->getAll($goods_id);
        }else{
            $list=[];
        }
        return $list;
    }
    
    
    
}