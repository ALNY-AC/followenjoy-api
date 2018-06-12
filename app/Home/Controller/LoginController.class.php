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
            '17521712398',
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
    
    
    
    // 将用户的手机号和unionid绑定
    public function binding(){
        
        // 注意：
        // 1、user_id 和 unionid 同时存在的账户，无法绑定，因为已经绑定过
        // 2、user_id 和 unionid 同时存在且相等的，可以绑定，为了修复之前的未绑定bug
        
        $user_id=I('user_id');//用户的id
        $unionid=I('unionid');//联盟id
        
        $User=D('User');//用户模型
        
        $where=[];
        $where['user_id']=$user_id;
        
        $user=$User->where($where)->find();//取出数据
        
        if($user){
            // user_id 已存在
            // 将 unionid 保存到 user_id 上
            // 前提是这个账号没有 unionid
            if(!$user['unionid']){
                // 这个账号没有 unionid ，所以可以绑定
                
                $where=[];
                $where['user_id']=$user_id;
                $save=[];
                $save['unionid']=$unionid;
                
                $User->where($save)->delete();
                $User->where($where)->save($save);
                $token=createToken($user_id);
                $res['user_id']=$user_id;
                $res['token']=$token;
                $res['res']=1;
            }else{
                // 此账户已经绑定，不能再次绑定
                $res['res']=-2;
            }
            
            
        }else{
            // 用户不存在
            // 可能用户之前是用旧版登录接口登录，或者是为绑定手机号的新用户
            // 再通过 unionid 查找用户
            
            $where=[];
            $where['unionid']=$unionid;
            
            $user=$User->where($where)->find();//取出数据
            
            if($user){
                // 第一次用微信登录的用户存在，绑定手机号
                
                $where=[];
                $where['unionid']=$unionid;
                $save=[];
                $save['user_id']=$user_id;
                $User->where($save)->delete();
                $User->where($where)->save($save);
                
                $token=createToken($user_id);
                $res['user_id']=$user_id;
                $res['token']=$token;
                $res['res']=1;
                
            }else{
                
                // 刚刚登录微信 unionid 找不到？参数有问题吧。
                
            }
            
            
        }
        
        echo json_encode($res);
        
    }
    
    /**
    * 获得手机验证码
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
    
    public function weixinLogin(){
        
        $unionid=I('unionid');
        $nickname=I('nickname');
        $headimgurl=I('headimgurl');
        
        // 先检查用户是否存在
        
        $where=[];
        $where['unionid']=$unionid;
        
        $User=D('User');
        $user=$User->where($where)->find();
        
        $isBinding=false;
        
        if(!$user){
            // 新用户，并且要求用户绑定
            $isBinding=true;
            $data['user_id']=$unionid;
            $data['user_name']=$nickname;
            $data['user_head']=$headimgurl;
            $data['unionid']=$unionid;
            $data['user_vip_level']=0;
            $data['user_money']=0;
            $data['add_time']=time();
            $data['edit_time']=time();
            $User->add($data);
            $user=$User->where($where)->find();
            
        }else{
            if(!$user['user_id']){
                // 没有user_id也是需要绑定
                $isBinding=true;
            }
            if($user['user_id'] == $user['unionid']){
                $isBinding=true;
            }
        }
        
        if($isBinding){
            $res['res']=1;
            $res['isBinding']=$isBinding;
            echo json_encode($res);
            exit;
        }
        
        $user=$User->where($where)->find();
        $token=createToken($user['user_id']);
        
        if($token){
            $res['res']=1;
            $res['token']=$token;
            $res['user_id']=$user['user_id'];
        }else{
            $res['res']=-1;
            $res['msg']=I();
        }
        
        echo json_encode($res);
        
    }
    
    
    
}