<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月28日14:06:31
* 最新修改时间：2018年4月28日14:06:31
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####权限组属性控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class PowerGroupPropsController extends CommonController{
    
    public function saveData(){
        $PowerGroupProps=D('PowerGroupProps');
        $result=$PowerGroupProps->saveData(I('power_group_id'),I('ids'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
}