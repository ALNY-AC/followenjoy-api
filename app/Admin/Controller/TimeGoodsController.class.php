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
        $result=$TimeGoods->create(I('data'));
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
        $data['where']=getKey();
        $result=$TimeGoods->getList($data);
        
        $res['count']=$TimeGoods->where($data['where'])->count()+0;
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
        $result=$TimeGoods->del(I('goods_id'),I('time_id'));
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