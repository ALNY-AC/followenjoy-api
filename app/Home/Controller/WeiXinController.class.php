<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月8日16:42:41
* 最新修改时间：2018年6月8日16:42:41
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####微信支付控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class WeiXinController extends Controller{
    
    public function notify(){
        F('weixin',I());
    }
    
    public function pay(){
        
        $jsApiParameters=weixin();
        $this->assign('jsApiParameters',$jsApiParameters);
        $this->display();
        
    }
    
    public function test(){
        $weixin= F('weixin');
        dump($weixin);
    }
    
    public function getsignkey(){
        
        
        getsignkey();
        
        
        
    }
}