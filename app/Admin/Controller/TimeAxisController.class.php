<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月6日17:06:00
* 最新修改时间：2018年6月6日17:06:00
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####时刻表控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class TimeAxisController extends CommonController{
    
    public function create(){
        $TimeAxis=D('TimeAxis');
        $result=$TimeAxis->create(I());
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        $TimeAxis=D('TimeAxis');
        $result=$TimeAxis->get();
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        $TimeAxis=D('TimeAxis');
        $result=$TimeAxis->getList();
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getAll(){
        $TimeAxis=D('TimeAxis');
        $result=$TimeAxis->getAll();
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        $TimeAxis=D('TimeAxis');
        $result=$TimeAxis->del();
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function saveData(){
        $TimeAxis=D('TimeAxis');
        $result=$TimeAxis->saveData();
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