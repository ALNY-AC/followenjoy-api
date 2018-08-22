<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月9日18:24:063
* 最新修改时间：2018年8月9日18:24:063
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品分组控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class GoodsGroupController extends CommonController{
    
    public function create(){
        $data=I('data');
        if(!$data){
            die;
        }
        $GoodsGroup=D('GoodsGroup');
        $data['add_time']=time();
        $data['edit_time']=time();
        $data['data_status']=1;
        $result=$GoodsGroup->add($data);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function get(){
        
        $GoodsGroup=D('GoodsGroup');
        $goods_group_id=I('goods_group_id');
        $where=[];
        $where['goods_group_id']=$goods_group_id;
        $result=$GoodsGroup->where($where)->find();
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        $GoodsGroup=D('GoodsGroup');
        $page_size=I('page_size',5,false);
        $page=I('page',1,false);
        $result  =  $GoodsGroup
        ->order('add_time desc')
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $res['total'] =  $GoodsGroup
        ->count()+0;
        
        $result=toTime($result);
        if($result!=false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getAll(){
        $GoodsGroup=D('GoodsGroup');
        $result  =  $GoodsGroup
        ->order('add_time desc')
        ->field('goods_group_id,group_name')
        ->select();
        
        if($result!=false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function saveData(){
        $GoodsGroup=D('GoodsGroup');
        $data=I('data');
        $goods_group_id=I('goods_group_id');
        unset($data['add_time']);
        unset($data['goods_group_id']);
        $data['edit_time']=time();
        $where=[];
        $where['goods_group_id']=$goods_group_id;
        $result=$GoodsGroup->where($where)->save($data);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        $goods_group_id=I('goods_group_id');
        $where=[];
        $where['goods_group_id']=['in',getIds($goods_group_id)];
        $GoodsGroup=D('GoodsGroup');
        $result=$GoodsGroup->where($where)->delete();
        
        // ===================================================================================
        // 删除分组的商品
        
        $GoodsGroupLink=D('GoodsGroupLink');
        $GoodsGroupLink->where($where)->delete();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function copy(){
        // ===================================================================================
        // 复制组
        $GoodsGroup=D('GoodsGroup');
        $result=$GoodsGroup->copy(I('goods_group_id'));
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
}