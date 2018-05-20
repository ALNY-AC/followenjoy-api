<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月28日13:06:02
* 最新修改时间：2018年4月28日13:06:02
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####物流信息控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class LogisticsController extends CommonController{
    
    public function saveData(){
        $Logistics=D('Logistics');
        $result=$Logistics->saveData(I('order_id'),I('save','',false));
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