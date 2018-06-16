<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:17:12
* 最新修改时间：2018年3月2日11:17:12
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####需要登录权限的继承本控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    
    //ThinkPHP提供的构造方法
    public function _initialize() {
        $isDebug=I('debug',false);
        if($isDebug){
            return;
        }
        
        $is=isUserLogin('user');
        
        
        if($is>0 ){
            //登录成功，继续操作
            //保存session
            session('user_id',$is['user_id']);
            session('shop_id',$is['shop_id']);
            return;
        }
        
        $res['res']=$is;
        if($is==-991){
            //令牌过期了
            $res['msg']='令牌过期了';
        }
        if($is==-992){
            //未登录
            $res['msg']='未登录';
        }
        echo json_encode($res);
        exit;
        
        
    }
    
}