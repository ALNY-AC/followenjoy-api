<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月4日16:12:20
* 最新修改时间：2018年4月4日16:12:20
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####消息控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
class MsgController extends Controller {
    
    //主
    public function getList(){
        
        $Msg=D('Msg');
        $msgs=$Msg->getList(I());
        
        if($msgs){
            $res['res']=count($msgs);
            $res['msg']=$msgs;
        }else{
            $res['res']=-1;
            $res['msg']=$msgs;
        }
        
        echo json_encode($res);
        
    }
    
    public function get(){
        
        $Msg=D('Msg');
        $result=$Msg->get(I('msg_id'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }

    //消息未读提示
    public function unreadNum(){
        $data = M('user_msg')->join('LEFT JOIN c_user_msg.msg_id = c_msg_push.msg_id')->where(['user_id'=>session('user_id')])->find();
        var_dump($data);
    }
    
    
}