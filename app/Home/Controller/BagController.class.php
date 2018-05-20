<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:16:39
* 最新修改时间：2018年3月2日11:16:39
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####购物单管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class BagController extends CommonController{
    
    
    //获得列表
    public function getList(){
        
        $Bag=D('bag');
        $bag=$Bag->getList();
        if($bag){
            $res['res']=count($bag);
            $res['msg']=$bag;
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    //更新数量
    public function updateNum(){
        
        $snapshot_id=I('snapshot_id');
        $count=I('count');
        
        $Bag=D('Bag');
        $Snapshot=D('Snapshot');
        
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $where['user_id']=session('user_id');
        
        $save=[];
        $save['edit_time']=time();
        
        $Bag->where($where)->save($save);
        
        $save['count']=$count;
        $result=$Snapshot->where($where)->save($save);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
            $res['sql']=$Snapshot->_sql();
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    //保存字段
    public function save(){
        
        $save=I('save','',false);
        $model=M('bag');
        $where=[];
        $where['user_id']=session('user_id');
        $where['bag_id']=$save['bag_id'];
        
        unset($save['bag_id']);
        
        $result=$model->where($where)->save($save);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
        }
        
        echo json_encode($res);
        
    }
    
    public function add(){
        
        $Bag=D('Bag');
        $Snapshot=D('Snapshot');
        
        $goods_id=I('goods_id');
        $sku_id=I('sku_id');
        $count=I('count');
        $snapshot_id=$Snapshot->create($goods_id,$sku_id,$count);
        
        $result=$Bag->create($snapshot_id);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $Bag=D('Bag');
        // ===================================================================================
        $result=$Bag->del(I('bag_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
    
}