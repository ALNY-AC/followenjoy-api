<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年11月17日
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
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    
    //ThinkPHP提供的构造方法
    public function _initialize() {
        
        $is=isUserLogin('admin');
        if($is>0){
            //登录成功，继续操作
            //保存session
            session('admin_id',$is['admin_id']);
            return;
        }
        
        $res['res']=$is;
        $res['msg']='令牌过期了';
        echo json_encode($res);
        exit;
        
        
    }
    
}