<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    
    /**
    * 统一登录接口 token 获取接口
    */
    public function token(){
        $res=[];
        $user_id=I('user_id');
        $secret=I('secret');
        $shop_id=I('shop_id');
        
        // ===================================================================================
        // 验证 secret 是否合法
        
        $Secret=D('Secret');
        $isSecret=$Secret->validate($secret,$user_id);
        
        if($isSecret){
            
            // ===================================================================================
            // 检查用户是否存在
            $User=D('User');
            $where=[];
            $where['user_id']=$user_id;
            $user=$User->where($where)->find();
            if($user){
                // 存在
                $user_id=$user['user_id'];
            }else{
                // 不存在
                // 创建一个用户
                $data=[];
                $data['user_id']=$user_id;
                $data['user_name']=$user_id;
                $User->create($data);
                $Coupon=D('Coupon');
                $Coupon->派发新用户大礼包($user_id);
                
            }
            
            // 可以获取token
            $Token=D('Token');
            $token=$Token->getToken($user_id);
            
            // ===================================================================================
            // 检查绑定关系
            
            $Vip=D('Vip');
            $Vip->linkShop($user_id,$shop_id);
            
            $res['res']=1;
            $res['user_id']=$user_id;
            $res['token']=$token;
            
        }else{
            // 不合法，不能获取token
            $res['res']=-1;
            $res['user_id']='';
            $res['token']='';
        }
        
        echo json_encode($res);
        
        
    }
    
    public function sinOut(){
        
        
        //获得传来的token
        $token=I('token');
        //获得传来的id
        $user_id=I('user_id');
        
        //创建token的控制器
        $Token=D('Token');
        //创建条件
        $where=[];
        $where['login_id']=$user_id;
        //删除token
        $result=$Token->where($where)->delete();
        
        //清空session
        session(null);
        
        if($result!==false){
            //退出成功
            $res['res']=1;
        }else{
            //退出失败
            $res['res']=-1;
        }
        
        echo json_encode($res);
    }
    
    
    
}