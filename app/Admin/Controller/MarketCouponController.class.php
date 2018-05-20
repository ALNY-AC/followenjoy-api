<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月2日11:37:30
* 最新修改时间：2018年5月2日11:37:30
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####优惠券发放器控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class MarketCouponController extends CommonController{
    // MarketCoupon
    
    public function creat(){
        $MarketCoupon=D('MarketCoupon');
        
        $result=$MarketCoupon->creat(I('data'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        
        echo json_encode($res);
    }
    public function saveData(){
        $MarketCoupon=D('MarketCoupon');
        
        $result=$MarketCoupon->saveData(I('id'),I('data'));
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
        $MarketCoupon=D('MarketCoupon');
        
        $result=$MarketCoupon->del(I('id'));
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
        $MarketCoupon=D('MarketCoupon');
        
        $data=I();
        $data['where']=getKey();
        $result=$MarketCoupon->getList($data);
        $res['count']=$MarketCoupon->where($data['where'])->count()+0;
        
        $result=$MarketCoupon->getList();
        
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
        $MarketCoupon=D('MarketCoupon');
        
        $result=$MarketCoupon->get(I('id'));
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
        $MarketCoupon=D('MarketCoupon');
        
        $result=$MarketCoupon->getAll(I(''));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    public function saveCouponGroup(){
        
        $MarketCoupon=D('MarketCoupon');
        $result=$MarketCoupon->saveCouponGroup(I('id'),I('data'));
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