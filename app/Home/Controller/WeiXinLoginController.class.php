<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月8日16:42:41
* 最新修改时间：2018年6月8日16:42:41
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####统一微信登录控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class WeiXinLoginController extends Controller{
    
    
    /**
    * 统一微信登录接口（跳转页面）
    */
    public function login(){
        Vendor('Weixin.WeiXinLogin');
        
        $shop_id=I('shop_id');
        $goIndex=I('goIndex','',true);
        
        $goIndex=urlencode($goIndex);
        
        $host='http://'.$_SERVER['SERVER_NAME'].'/';
        
        $redirect_uri= $host."home/WeiXinLogin/redirct?shop_id=$shop_id&goIndex=$goIndex";
        $weixinLogin = new \WeiXinLogin();
        $weixinLogin->setRedirectUrl($redirect_uri,false);
        $url=$weixinLogin->login(false);
        
        
    }
    
    /**
    * 统一微信登录接口回跳地址
    */
    public function redirct(){
        
        Vendor('Weixin.WeiXinLogin');
        $weixinLogin = new \WeiXinLogin();
        $weixinLogin->initLoginInfo();
        $userInfo=$weixinLogin->getUserInfo();
        $shop_id=I('shop_id');
        $goIndex=I('goIndex');
        
        $unionid=$userInfo['unionid'];
        F('userInfo'.$unionid,json_encode($userInfo));
        
        $host='http://test.q.followenjoy.cn/#/';
        if($shop_id){
            $url= $host."WeiXinLogin?unionid=$unionid&shop_id=$shop_id&goIndex=$goIndex";
        }else{
            $url= $host."WeiXinLogin?unionid=$unionid&shop_id=-1&goIndex=$goIndex";
        }
        echo "<script>window.location.replace='$url'</script>";
    }
    
    /**
    * 获得微信用户信息
    */
    public function getUserInfo (){
        $unionid=I('unionid');
        $userinfo=F('userInfo'.$unionid);
        
        if($userinfo){
            $res['res']=1;
            $res['userinfo']=json_decode($userinfo,true);
        }else{
            $res['res']=-1;
            $res['userinfo']='';
        }
        
        echo json_encode($res);
    }
    
    public function setUserInfo(){
        
        $unionid=I('unionid');
        $userInfo=I('userInfo');
        F('userInfo'.$unionid,json_encode($userInfo));
        $res['res']=1;
        echo json_encode($res);
        
    }
    
    /**
    * 统一微信登录换取secret接口
    */
    public function authorize(){
        
        // ===================================================================================
        // 创建模型
        $User=D('User');
        
        // ===================================================================================
        // 接收参数
        $unionid=I('unionid');
        
        // ===================================================================================
        // 检查要获取的用户是否存在
        
        // ===================================================================================
        // 创建条件
        $where=[];
        $where['unionid']=$unionid;
        $user=$User->where($where)->find();
        if($user){
            
            // 存在
            // 调用 统一secret创建接口
            $user_id=$user['user_id'];
            
            $Secret=D('Secret');
            $secret=$Secret->create($user_id);
            $res['res']=1;
            $res['secret']=$secret;
            $res['user_id']=$user_id;
            
        }else{
            
            // 不存在
            // 返回不存在
            $res['res']=-1;
            $res['secret']='';
            $res['user_id']='';
            
        }
        
        echo json_encode($res);
        
    }
    
}