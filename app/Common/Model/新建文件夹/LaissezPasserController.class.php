<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月12日13:00:01
* 最新修改时间：2018年6月12日13:00:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####特权阶级控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class LaissezPasserController extends CommonController{
    
    public function create(){
        $LaissezPasser=D('LaissezPasser');
        $result=$LaissezPasser->create(I('data'));
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
        $LaissezPasser=D('LaissezPasser');
        $result=$LaissezPasser->getList(I());
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
        $LaissezPasser=D('LaissezPasser');
        $result=$LaissezPasser->del(I('id'));
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
        $LaissezPasser=D('LaissezPasser');
        $result=$LaissezPasser->saveData(I('id'),I('data'));
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
}