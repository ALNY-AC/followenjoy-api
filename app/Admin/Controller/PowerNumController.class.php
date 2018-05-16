<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月28日14:12:09
* 最新修改时间：2018年4月28日14:12:09
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####权限编号#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class PowerNumController extends CommonController{
    
    
    public function creat(){
        $PowerNum=D('PowerNum');
        $result=$PowerNum->creat(I('data'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    public function saveData(){
        $PowerNum=D('PowerNum');
        $result=$PowerNum->saveData(I('power_num_id'),I('data'));
        if($result!=false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    public function del(){
        $PowerNum=D('PowerNum');
        $result=$PowerNum->del(I('ids'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    public function getAll(){
        $PowerNum=D('PowerNum');
        $result=$PowerNum->getAll(I(''));
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