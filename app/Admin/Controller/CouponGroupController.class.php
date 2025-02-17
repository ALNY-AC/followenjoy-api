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
namespace Admin\Controller;
use Think\Controller;
class CouponGroupController extends CommonController{
    // CouponGroup
    public function creat(){
        $CouponGroup=D('CouponGroup');
        $result=$CouponGroup->creat(I('data'));
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
        
        $CouponGroup=D('CouponGroup');
        $result=$CouponGroup->saveData(I('id'),I('data'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        $CouponGroup=D('CouponGroup');
        
        $data=I();
        $data['where']=getKey();
        $result=$CouponGroup->getList($data);
        $res['count']=$CouponGroup->where($data['where'])->count()+0;
        
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
    
    
    public function del(){
        $CouponGroup=D('CouponGroup');
        $result=$CouponGroup->del(I('id'));
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
    
    
    
    
}