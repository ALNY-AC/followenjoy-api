<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月23日16:15:25
* 最新修改时间：2018年4月23日16:15:25
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####热搜控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class HotLabelController extends CommonController{
    
    public function getList(){
        $HotLabel=D('HotLabel');
        $result=$HotLabel->getList(I('','',false));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
}