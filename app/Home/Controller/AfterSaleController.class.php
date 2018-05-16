<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月27日01:20:09
* 最新修改时间：2018年4月27日01:20:09
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####售后#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class AfterSaleController extends CommonController{
    
    public function creat(){
        
        $AfterSale=D('AfterSale');
        $result=$AfterSale->creat(I('add','',false));
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
        
        $AfterSale=D('AfterSale');
        $result=$AfterSale->saveData(I('order_id'),I('save','',false));
        
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