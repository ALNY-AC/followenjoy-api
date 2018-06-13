<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月27日23:32:56
* 最新修改时间：2018年5月27日23:32:56
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####限时购商品控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class TimeGoodsController extends CommonController{
    
    public function create(){
        $TimeGoods=D('TimeGoods');
        $result=$TimeGoods->create(I());
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
        $TimeGoods=D('TimeGoods');
        
        $data=I();
        
        $result=$TimeGoods->getList($data);
        
        $start_time=$data['start_time'];
        
        $where=[];
        $where['start_time']=$start_time;
        $where['is_show']=1;
        
        
        $res['count']=$TimeGoods->where($where)->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getAll(){
        $TimeGoods=D('TimeGoods');
        $data=I();
        $result=$TimeGoods->getAll($data);
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function getData(){
        
        $TimeGoods=D('TimeGoods');
        
        $result=$TimeGoods->getData(I());
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function get(){
        
        $TimeGoods=D('TimeGoods');
        
        $result=$TimeGoods->get(I());
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getPlus(){
        
        $TimeGoods=D('TimeGoods');
        
        $data=I();
        
        $result=$TimeGoods->getPlus($data);
        
        $start_time=$data['start_time'];
        
        $where=[];
        $where['start_time']=$start_time;
        $where['is_show']=1;
        
        $res['count']=$TimeGoods->where($where)->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    
    public function getTomorrow(){
        
        $TimeGoods=D('TimeGoods');
        $result=$TimeGoods->getTomorrow(I());
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}