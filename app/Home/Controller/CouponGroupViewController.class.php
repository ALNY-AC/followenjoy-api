<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月22日13:20:07
* 最新修改时间：2018年8月22日13:20:07
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####优惠券组领取控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CouponGroupViewController extends Controller{
    
    /**
    * 显示文案页面。
    * @author 徐开兵
    */
    public function show(){
        $this->assign('coupon_group_id',I('coupon_group_id'));
        $this->display();
    }
    
    
    /**
    * 显示战略key页面
    * @author 吴杰
    */
    public function code(){
        $coupon_group_id = I('coupon_group_id','','');
        $where['coupon_group_id'] = $coupon_group_id;
        $data = D('coupon_group')->where($where)->field('coupon_group_id,stock,coupon_group_key')->find();
        $isEmpty=false;
        if($data['stock'] <= 0){
            $isEmpty = true;
        }
        
        $msg = $data['coupon_group_key'];
        $this->assign('msg',$msg);
        $this->assign('isEmpty',$isEmpty);
        
        $this->display();
    }
    
}