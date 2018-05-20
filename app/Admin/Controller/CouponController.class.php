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
namespace Admin\Controller;
use Think\Controller;
class CouponController extends CommonController{
    
    public function add(){
        
        $Coupon=D('Coupon');
        $add=I('add');
        
        $add["coupon_id"]=getMd5('coupon');
        $add["state"]=1;
        $add["add_time"]=time();
        $add["edit_time"]=time();
        $result=$Coupon->add($add);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function save(){
        
        $Coupon=D('Coupon');
        
        $where=I('where');
        $save=I('save');
        $save['edit_time']=time();
        $result=$Coupon->where($where)->save($save);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $Coupon=D('Coupon');
        $coupon_id=I('coupon_id');
        $where=[];
        $where['coupon_id']=['in',$coupon_id];
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
        $data['where']=getKey();
        
        $result=$Coupon->getList($data);
        $res['count']=$Coupon->where($data['where'])->count()+0;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function groupToCode(){
        $Coupon=D('Coupon');
        $result=$Coupon->groupToCode(I('coupon_group_id'),I('count'));
        
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