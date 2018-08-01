<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月9日09:30:33
* 最新修改时间：2018年5月9日09:30:33
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####支付控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class PayController extends Controller{
    
    // 支付回调函数
    public function alipay_notify(){
        
        
    }
    
    public function pay(){
        
    }
    
    public function getPayInfo(){
        
        $pay_id=I('pay_id');
        $where=[];
        $where['pay_id']=$pay_id;
        
        $Pay=D('Pay');
        $result=$Pay->where($where)->find();
        
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