<?php
namespace Home\Model;
use Think\Model;
class BagModel extends Model {
    
    
    public function getList($where=[]){
        $where['user_id']=session('user_id');
        
        $bag=$this
        ->where($where)
        ->order('add_time desc')
        ->select();
        //构建商品数据
        $Snapshot=D('Snapshot');
        for ($i=0; $i <count($bag) ; $i++) {
            $snapshot_id  = $bag[$i]['snapshot_id'];
            $snapshot = $Snapshot->get($snapshot_id);
            $bag[$i]['snapshot']=$snapshot;
        }
        $bag=toTime($bag);
        
        return $bag;
        
    }
    
    //添加一条购物袋数据
    public function create($snapshot_id){
        //相同用户，相同商品，相同sku，组成一个购物车和购物车id
        $user_id=session('user_id');
        
        $where=[];
        $where['user_id']=$user_id;
        $where['snapshot_id']=$snapshot_id;
        //如果已经添加，就不再次添加
        
        $result=$this->where($where)->find();
        
        if(!$result){
            //没有数据，添加
            $bag_id=getMd5('bag');
            $add['bag_id']=$bag_id;
            $add['snapshot_id']=$snapshot_id;
            $add['user_id']=$user_id;
            $add['add_time']=time();
            $add['edit_time']=time();
            $result=$this->add($add);
        }
        return $result;
        
    }
    
    
    public function del($bag_id){
        
        $Snapshot=D('Snapshot');
        // ===================================================================================
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=['in',$bag_id];
        // ===================================================================================
        //判断快照是否已经有orderID了，如果有了就不能删除快照只删除购物车
        //先找购物袋数据
        $bags=$this->where($where)->select();
        
        foreach ($bags as $key => $bag) {
            $snapshot_id=$bag['snapshot_id'];
            $where=[];
            $where['snapshot_id']=$snapshot_id;
            $where['user_id']=session('user_id');
            $snapshot=$Snapshot->where($where)->find();
            if(!$snapshot['order_id']){
                //没有order_id，就删除
                $Snapshot->where($where)->delete();
            }
        }
        
        // ===================================================================================
        //统一删掉购物车数据
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=['in',$bag_id];
        $result=$this->where($where)->delete();
        return $result;
        
    }
    
    
    
    
}