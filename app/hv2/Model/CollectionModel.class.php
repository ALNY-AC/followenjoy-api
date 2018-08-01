<?php
namespace Home\Model;
use Think\Model;
class CollectionModel extends Model {
    
    
    public function _initialize (){}
    
    public function change($data){
        $where=[];
        $where['goods_id']=$data['goods_id'];
        $where['user_id']=session('user_id');
        
        
        $result=$this->where($where)->find();
        if($result){
            //已存在，就取消收藏
            $result= $this->where($where)->delete();
            if($result){
                return true;
            }else{
                return false;
            }
            
        }else{
            //不存在，就添加收藏
            $add=[];
            $add['goods_id']=$data['goods_id'];
            $add['user_id']=session('user_id');
            $add['add_time']=time();
            $add['edit_time']=time();
            $result= $this->add($add);
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function getList($data){
        
        $where=[];
        $where['user_id']=session('user_id');
        
        $goodsIds=$this->order('add_time desc')->where($where)->getField('goods_id',true);
        
        if(!$goodsIds){
            return [];
        }
        
        $where=[];
        $where['goods_id']=['in',getIds($goodsIds)];
        
        $data['where']=$where;
        $Goods=D('Goods');
        return $Goods->getAll($data);
    }
    
}