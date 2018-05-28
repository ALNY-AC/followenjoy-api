<?php
namespace Admin\Model;
use Think\Model;
class TimeGoodsModel extends Model {
    
    
    public function _initialize (){}
    
    public function create($data){
        
        
        
        $adds=[];
        
        $goods_id=$data['goods_id'];
        
        foreach ($goods_id as $k => $v) {
            
            $time_goods_id=getMd5('time_goods_id');
            $item=[];
            $item['time_goods_id']=$time_goods_id;
            $item['time_id']=$data['time_id'];
            $item['goods_id']=$v;
            $item['add_time']=time();
            $item['edit_time']=time();
            $adds[]=$item;
        }
        
        // ===================================================================================
        // 先删除关联
        
        $where=[];
        $where['time_id']=$data['time_id'];
        $this->where($where)->delete();
        
        if($this->addAll($adds)){
            return true;
        }else{
            return false;
        }
    }
    
    public function getList($data){
        $Goods=D('Goods');
        $time_id=$data['time_id'];
        $goods_id=$this->where(['time_id'=>$time_id])->getField('goods_id',true);
        
        if($goods_id){
            $data['where']['goods_id']=['in',$goods_id];
        }else{
            $list=[];
        }
        
        $list=$Goods->getList($data);
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
    
    public function del($goods_id,$time_id){
        $where=[];
        $where['goods_id']=['in',getIds($goods_id)];
        $where['time_id']=$time_id;
        return $this->where($where)->delete();
    }
    
}