<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月18日00:03:08
* 最新修改时间：2018年5月18日00:03:08
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####主会场控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class HallController extends CommonController{
    
    public function get(){
        
        $Hall=D('Hall');
        $data=$Hall->find();
        $res['res']=1;
        $res['msg']=$data;
        echo json_encode($res);
        
    }
    
    
    
}