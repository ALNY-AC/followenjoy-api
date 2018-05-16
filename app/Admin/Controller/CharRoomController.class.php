<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月2日17:59:08
* 最新修改时间：2018年5月2日17:59:08
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####会话控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class CharRoomController extends CommonController{
    // CharRoom
    public function creat(){
        $CharRoom=D('CharRoom');
        $result=$CharRoom->creat(I('data'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function assign(){
        $CharRoom=D('CharRoom');
        $result=$CharRoom->assign(I('char_room_id'),I('admin_id'));
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
        $CharRoom=D('CharRoom');
        $result=$CharRoom->del(I('id'));
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