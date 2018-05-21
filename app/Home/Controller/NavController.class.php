<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月21日11:11:46
* 最新修改时间：2018年5月21日11:11:46
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####导航控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class NavController extends CommonController{
    
    public function getList(){
        $Nav=D('Nav');
        $result=$Nav->getList(I());
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
        $Nav=D('Nav');
        $result=$Nav->get(I('nav_id'));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getGoods(){
        $Nav=D('Nav');
        $result=$Nav->getGoods(I('nav_id'),I());
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