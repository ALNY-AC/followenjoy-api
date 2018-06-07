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
namespace Admin\Controller;
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
        
        $res['count']=$TimeGoods->where(['start_time'=>$start_time])->count()+0;
        
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
    
    public function del(){
        $TimeGoods=D('TimeGoods');
        $result=$TimeGoods->del(I('goods_id'),I('start_time'));
        if($result){
            $res['res']=1;
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
    
    public function saveData(){
        
        $TimeGoods=D('TimeGoods');
        
        $result=$TimeGoods->saveData(I('goods_id'),I('start_time'),I('data'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function test(){
        
        die;
        $TimeGoods=D('TimeGoods');
        
        $list=$TimeGoods->select();
        
        foreach ($list as $k => $v) {
            
            $where=[];
            $where['time_goods_id']=$v['time_goods_id'];
            
            $data=[];
            $end_time=strtotime("+1 day",$v['start_time']);
            // $data['start_time']=$end_time;
            $data['end_time']=$end_time;
            
            $TimeGoods->where($where)->save($data);
            
        }
        $list=$TimeGoods->select();
        
        dump($list);
        
    }
}