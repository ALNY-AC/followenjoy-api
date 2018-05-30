<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日01:17:26
* 最新修改时间：2018年4月9日01:17:26
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####优惠券控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CouponController extends CommonController{
    
    
    public function del(){
        
        $Coupon=D('Coupon');
        $coupon_id=I('coupon_id');
        $where=[];
        $where['coupon_id']=$coupon_id;
        $where['user_id']=session('user_id');
        $result=$Coupon->where($where)->delete();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        
        $Coupon=D('Coupon');
        $data=I();
        $data['time']=true;
        $couponList= $Coupon->getUserList($data);
        $res['count']= $Coupon->count()+0;
        
        if($couponList !== false){
            $res['res']=count($couponList);
            $res['msg']=$couponList;
        }else{
            $res['res']=-1;
            $res['msg']=$couponList;
        }
        echo json_encode($res);
        
    }
    
    
}