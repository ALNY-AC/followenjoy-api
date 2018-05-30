<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    
    
    public function test(){
        $res['res']=1;
        $res['get']=I('get.');
        $res['post']=I('post.');
        echo json_encode($res);
    }
    
    public function login(){
        
        $user_id=I('user_id');
        $user_code=I('user_code');
        
        //检查参数
        if( !$user_id || !$user_code ){
            //少一样都不行
            $res['res']=-2;
            echo json_encode($res);
            die;
        }else{
            
            // 有验证码
            // ===================================================================================
            // 验证短信验证码
            $Code=D('Code');
            $isSuccess= $Code->validate($user_id,$user_code)>0;
            
            // ===================================================================================
            // 测试账户：
            $testID=[
            '13914896237',
            '17621643903',
            "18751178711",
            "13251603333",
            "15727304388",
            "15216776703",
            ];
            
            if(in_array($user_id,$testID)){
                $isSuccess=true;
            }
            
            if($isSuccess){
                //验证码正确
                //生成 token
                //换取token
                
                $token=createToken($user_id);
                
                if($token){
                    $res['res']=1;
                    $res['token']=$token;
                }else{
                    $res['res']=-1;
                    $res['msg']=I();
                }
                
                echo json_encode($res);
                
            }else{
                //验证码不正确
                $res['res']=-3;
                echo json_encode($res);
            }
        }
        
        
    }
    
    /**
    * 获得手机验证码0
    */
    public function getCode(){
        
        $user_id=I('user_id');
        $share_id=I('share_id');
        
        // ===================================================================================
        // 如果没有此用户，那么这个用户在这里就等于注册
        
        $User=D('User');
        $where=[];
        $where['user_id']=$user_id;
        $isUser=$User->where($where)->find();
        
        if(!$isUser){
            // ===================================================================================
            // 没有用户，需要注册
            $add=[];
            $add['user_id']=$user_id;
            $add['user_name']=$user_id;
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['user_type']=0;
            $Coupon=D('Coupon');
            
            // 派发大礼包给新注册
            $Coupon->派发新用户大礼包($user_id);
            
            // 派发大礼包给邀请人
            // 先判断邀请人是不是会员，只有会员邀请别人才有优惠券
            // share_id
            
            if($share_id){
                // 绑定
                $where=[];
                $where['user_id']=$share_id;
                $share_user=$User->where($where)->find();
                if($share_user){
                    if($share_user['user_vip_level']>0){
                        // 分享人是会员
                        $UserSuper=D('UserSuper');
                        $data=[];
                        $data['user_id']=$user_id;
                        $data['super_id']=$share_id;
                        $data['add_time']=time();
                        $data['edit_time']=time();
                        $UserSuper->add($data);
                    }
                }
                
            }
            
            $User->add($add);
        }
        
        // ===================================================================================
        // 发送短信
        
        $Code=D('Code');
        $res=$Code->pushCode($user_id);
        
        echo json_encode($res);
        
    }
    /**
    * 判断是否登录
    */
    public function islogin(){
        
        $is=isUserLogin('user');
        if($is<0){
            $res['res']= $is;
        }else{
            $res['res']= 1;
        }
        $res['res']= 1;
        echo json_encode($res);
        
    }
    
    public function sinOut(){
        
        
        //获得传来的token
        $token=I('token');
        //获得传来的id
        $user_id=I('user_id');
        
        //创建token的控制器
        $model=M('token');
        //创建条件
        $where=[];
        $where['login_id']=$user_id;
        //删除token
        $result=$model->where($where)->delete();
        
        //清空session
        session(null);
        
        if($result!==false){
            //退出成功
            $res['res']=-991;
        }else{
            //退出失败
            $res['res']=-1;
        }
        
        echo json_encode($res);
    }
    
}