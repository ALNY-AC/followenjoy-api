<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月23日16:15:25
* 最新修改时间：2018年4月23日16:15:25
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####热搜控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class HotLabelController extends CommonController{
    
    public function creat(){
        $HotLabel=D('HotLabel');
        $result=$HotLabel->creat(I('add','',false));
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
        $HotLabel=D('HotLabel');
        $result=$HotLabel->saveData(I('hot_label_id','',false),I('save','',false));
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
        $HotLabel=D('HotLabel');
        $result=$HotLabel->getList(I('','',false));
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
        $HotLabel=D('HotLabel');
        $result=$HotLabel->get(I('hot_label_id','',false));
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
        $HotLabel=D('HotLabel');
        $result=$HotLabel->del(I('ids','',false));
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