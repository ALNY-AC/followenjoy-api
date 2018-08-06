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
class CodeController extends Controller{
    
    public function get(){
        
        if(I('phone')){
            $phone=I('phone');
        }else{
            $phone=I('user_id');
        }
        $Code=D('Code');
        $res=$Code->pushCode($phone);
        echo json_encode($res);
    }
    
    public function validate(){
        $Code=D('Code');
        
        $phone=I('user_id');
        $user_code=I('user_code');
        
        $isSuccess= $Code->validate($phone,$user_code)>0;
        // 创建票据
        if($isSuccess){
            $Secret=D('Secret');
            $secret=$Secret->create($phone);
            $res['res']=1;
            $res['secret']=$secret;
        }else{
            //验证码不正确
            $res['res']=-1;
            $res['secret']='';
        }
        
        echo json_encode($res);
    }
    
    public function authorize(){
        $Code=D('Code');
        $phone=I('phone');
        $user_code=I('user_code');
        
        $isSuccess= $Code->validate($phone,$user_code)>0;
        
        // 创建票据
        if($isSuccess){
            $Secret=D('Secret');
            $secret=$Secret->create($phone);
            $res['res']=1;
            $res['secret']=$secret;
        }else{
            //验证码不正确
            $res['res']=-1;
            $res['secret']='';
        }
        
        echo json_encode($res);
    }
    
    
}