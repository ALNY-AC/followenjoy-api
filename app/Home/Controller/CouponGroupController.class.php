<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月2日08:38:13
* 最新修改时间：2018年5月2日08:38:13
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####优惠券组控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class CouponGroupController extends Controller{
    // CouponGroup
    
    
    public function getList(){
        $CouponGroup=D('CouponGroup');
        
        $data=I();
        $data['where']=getKey();
        $result=$CouponGroup->getList($data);
        $res['count']=$CouponGroup->where($where)->count()+0;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    public function get(){
        $CouponGroup=D('CouponGroup');
        $result=$CouponGroup->get(I('id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    public function getAll(){
        $CouponGroup=D('CouponGroup');
        $result=$CouponGroup->getAll(I());
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    //兑换券
    public function coinCertificate(){
        try{
            $user_id = session('user_id');
            $coupon_group_key = I('coupon_group_key','','');
            //是否有分组
            $where['coupon_group_id'] = $coupon_group_key;
            $is_group = D('coupon_group')->where($where)->find();
            if(!$is_group){
                throw new Exception('兑换券不存在','-4');
            }
            //数量
            if($is_group['stock'] <= 0){
                throw new Exception('兑换券已用完','-3');
            }
            //是否兑换过
            $has['user_id'] = $user_id;
            $has['coupon_group_id'] = $is_group['coupon_group_id'];
            $isOwn = D('coupon')->where($has)->find();
            if($isOwn){
                throw new Exception('您已兑换','-2');
            }
            $out = D('coupon_group')->where(['coupon_group_id'=>$is_group['coupon_group_id']])->setDec('stock',1); // 用户的积分减1
            if($out){
                $isNum = D('Coupon')->groupToCode($is_group['coupon_group_id'],1,$user_id);
                if($isNum){
                    throw new Exception('兑换成功','1');
                }
            }
            throw new Exception('兑换失败','-1');

        }catch (Exception $e){
            echo json_encode(['msg'=>$e->getMessage(),'res'=>$e->getCode()]);
        }

    }
    
    
}