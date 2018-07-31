<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月25日09:48:11
* 最新修改时间：2018年7月25日09:48:11
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####展位控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class BoothController extends CommonController{
    
    public function create(){
        $Booth=D('Booth');
        $result=$Booth->create(I('data'));
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
        $Booth=D('Booth');
        $result=$Booth->get(I('booth_id'));
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
        $Booth=D('Booth');
        $result=$Booth->del(I('ids'));
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
        $Booth=D('Booth');
        $result=$Booth->saveData(I('booth_id'),I('data'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getAll(){
        $Booth=D('Booth');
        $result=$Booth->getAll(I('booth_group_id'));
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