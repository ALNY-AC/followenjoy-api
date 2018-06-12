<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月12日05:18:14
* 最新修改时间：2018年6月12日05:18:14
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####Token控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class TokenController extends Controller{
    
    public function get(){
        
        $data=[];
        $data['user_id']=I('user_id');
        $data['app_secret']=I('app_secret');
        
        $Token=D('Token');
        $token=$Token->create($data);
        
        if($token){
            $res['res']=1;
            $res['token']=$token;
        }else{
            $res['res']=-1;
            $res['msg']=0;
        }
        echo json_encode($res);
        
    }
    
    
}