<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日13:59:00
* 最新修改时间：2018年4月9日13:59:00
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####限时购控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class TimeController extends CommonController{
    
    public function getList(){
        $Time=D('Time');
        $times=$Time->getList();
        if($times!==false){
            $res['res']=count($times);
            $res['msg']=$times;
        }else{
            $res['res']=-1;
            $res['msg']=$times;
        }
        echo json_encode($res);
    }
    
    
}