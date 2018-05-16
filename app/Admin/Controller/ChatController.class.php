<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月2日18:08:22
* 最新修改时间：2018年5月2日18:08:22
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####聊天控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class ChatController extends CommonController{
    
    public function creat(){
        $Chat=D('Chat');
        $result=$Chat->creat(I('data'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function update(){
        $Chat=D('Chat');
        
        $res=$Chat->update(I());
        
        echo json_encode($res);
    }
    
    public function history(){
        $Chat=D('Chat');
        $result=$Chat->history(I());
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
        $Chat=D('Chat');
        $result=$Chat->del(I('id'));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    public function getData(){
        $Chat=D('Chat');
        $result=$Chat->getData(I('chat_id'));
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