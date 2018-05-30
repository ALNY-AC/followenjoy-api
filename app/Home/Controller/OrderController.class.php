<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:17:34
* 最新修改时间：2018年3月2日11:17:34
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####订单管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class OrderController extends CommonController{
    
    
    
    
    public function test(){
        
        
        
    }
    
    //获得添加订单页的数据包
    public function getAddPacket(){
        $Address=M('Address');
        $snapshot_id=I('snapshot_id');
        
        $where=[];
        $where['user_id']=session('user_id');
        // ===================================================================================
        // 地址数据
        $addressList=$Address->where($where)->select();
        
        // ===================================================================================
        // 商品快照
        $Snapshot=D('Snapshot');
        $snapshots=$Snapshot->getList($snapshot_id);
        
        // ===================================================================================
        // 找优惠券信息
        $Coupon=D('Coupon');
        $couponList= $Coupon->getUserList(['time'=>false]);
        
        if($snapshots){
            $res['res']=count($snapshots);
            $res['snapshots']=$snapshots;
            $res['addressList']=$addressList;
            $res['couponList']=$couponList;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    //获得列表
    public function getList(){
        
        $data=I('','',false);
        $Order=D('order');
        $orders=$Order->getList($data);
        
        if($orders!==false){
            $res['res']=count($orders);
            $res['msg']=$orders;
        }else{
            $res['res']=-1;
            $res['msg']=$orders;
        }
        
        echo json_encode($res);
        
    }
    
    public function get(){
        
        $Order=D('Order');
        $order_id=I('order_id');
        $orderInfo=$Order->get($order_id);
        
        $res['res']=1;
        $res['msg']=$orderInfo;
        
        echo json_encode($res);
        
    }
    
    //保存字段
    public function save(){}
    
    public function create(){
        
        $post=I('','',false);
        //根据sku组成订单详情表
        $Order=D('order');
        $pay_id=$Order->create($post);
        
        if($pay_id){
            $res['res']=1;
            $res['msg']=$pay_id;
        }else{
            $res['res']=-1;
            $res['msg']=$pay_id;
        }
        
        echo json_encode($res);
        
    }
    
    public function pay(){
        
        $pay_id=I('pay_id','',false);
        $Order=D('order');
        $result=$Order->pay($pay_id);
        $res['res']=1;
        $res['msg']=$result;
        echo json_encode($res);
    }
    
    public function cancel(){
        //取消订单
        $pay_id=I('pay_id');
        $Order=D('order');
        $result=$Order->cancel($pay_id);
        $res['res']=1;
        $res['msg']=$result;
        echo json_encode($res);
    }
    
    // 修改单个订单价格，并且重新计算支付单
    public function saveOrderPrice(){
        $Order=D('order');
        $order_id=I('order_id');
        $price=I('price');
        $result=$Order->saveOrderPrice($order_id,$price);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function okOrder(){
        
        // 1、待付款
        // 2、待发货
        // 3、待收货
        // 4、交易成功
        // 5、退款/退货
        // 6、已关闭
        // 7、已退款
        // 8、退款失败
        
        $order_id=I('order_id');
        $where=[];
        $where['order_id']=$order_id;
        $Order=D('Order');
        
        $data=[];
        $data['state']=4;
        $result=$Order->where($where)->save($data);
        
        
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