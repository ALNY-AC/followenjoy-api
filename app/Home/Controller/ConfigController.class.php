<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月19日09:58:50
* 最新修改时间：2018年4月19日09:58:50
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####配置控制器#####
* @author 代码狮
*
*/

namespace Home\Controller;
use Think\Controller;
class ConfigController extends CommonController{
    
    public function get(){
        
        $Config=D('Config');
        $where=[];
        $where['id']=1;
        $result=$Config->where($where)->find();
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
}