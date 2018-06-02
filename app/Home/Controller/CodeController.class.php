<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月2日17:39:10
* 最新修改时间：2018年6月2日17:39:10
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####短信验证码控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CodeController extends CommonController{
    
    public function get(){
        
        $user_id=I('user_id');
        $Code=D('Code');
        $res=$Code->pushCode($user_id);
    }
    
    public function validate(){
        $Code=D('Code');
        $user_id=I('user_id');
        $user_code=I('user_code');
        
        $isSuccess= $Code->validate($user_id,$user_code)>0;
        
        if($isSuccess){
            $res['res']=1;
        }else{
            //验证码不正确
            $res['res']=-1;
        }
        echo json_encode($res);
    }
    
    
}