<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月27日00:11:13
* 最新修改时间：2018年4月27日00:11:13
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####支付单控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class PayController extends CommonController{
    
    public function saveData(){
        $Pay=D('Pay');
        
        $result=$Pay->saveData(I('pay_id'),I('save'));
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