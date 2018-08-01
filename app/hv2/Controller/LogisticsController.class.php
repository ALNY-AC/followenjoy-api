<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月19日13:40:12
* 最新修改时间：2018年5月19日13:40:12
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
namespace Home\Controller;
use Think\Controller;
class LogisticsController extends CommonController{
    
    public function get(){
        
        $Logistics=D('Logistics');
        $result=$Logistics->getInfo(I());
        
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