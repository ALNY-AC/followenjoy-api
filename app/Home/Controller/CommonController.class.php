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
        
        
        $token=I('token');
        $user_id=I('user_id');
        // ===================================================================================
        // 验证用户token是否正确
        $Token=D('Token');
        $token=$Token->validate($token,$user_id);
        
        if($token){
            // 可以登录
            $User=D('User');
            $where=[];
            $where['user_id']=$user_id;
            $user=$User->where($where)->field('user_id,shop_id')->find();
            session('user_id',$user['user_id']);
            session('shop_id',$user['shop_id']);
            return;
        }else{
            // 登录失效
            $res['res']=-991;
            echo json_encode($res);
            exit;
        }
    }
    
}