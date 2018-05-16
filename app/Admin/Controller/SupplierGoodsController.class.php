<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月15日22:48:13
* 最新修改时间：2018年4月15日22:48:13
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####供货商&商品控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class SupplierGoodsController extends CommonController{
    
    public function getList(){
        $SupplierGoods=D('SupplierGoods');
        $result=$SupplierGoods->getList();
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function del(){
        
        $SupplierGoods=D('SupplierGoods');
        $result=$SupplierGoods->del(I('supplier_id'),I('goods_id'));
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