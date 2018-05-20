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
        
        Vendor('VIP.VIP');
        
        //初始化vip对象
        $conf=[];
        $conf['userId']='12138';
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        
        //==================省钱回扣功能展示============================
        ec("==================省钱回扣功能展示============================");
        //订单的原本总价`
        $orderMoney=100;
        //自购省钱，当此用户购买了一个商品并且支付成功后，调用此函数，调用后上级得到回扣。
        $vip->discountRebate($orderMoney);
        
        
        // //==================下级列表功能展示============================
        // ec("==================下级列表功能展示============================");
        // //初始化下级列表
        // $vip->initSubList();
        // //获得下级列表
        // $subList=$vip->getSubList();
        // // dump($subList);
        
        //==================销售奖功能展示============================
        ec("==================销售奖功能展示============================");
        
        //订单的原本总价
        $orderMoney=100;
        // 什么时候调用？
        // 当分享出去的商品被购买后，先取得分享人的 vip 对象，然后取得分享人 vip 对象的 super，
        // 然后调用这个 super 的 salesAward() 函数。
        
        //现在，假设分享人的id为 12132，那么调用当前对象的 super的指定函数
        $vip->getSuper()->salesAward($orderMoney);
        
        
        //==================自己发展团队功能展示============================
        ec("==================自己发展团队功能展示============================");
        
        // //当 12132 这个用户成功支付后，创建这个用户的 vip 对象，然后取得当前用户的 super ，然后调用 super 的 邀请人得钱奖()
        
        $conf=[];
        $conf['userId']='12136';
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        $vip->getSuper()->邀请人得钱奖($vip);
        
        for ($i=0; $i < 50; $i++) {
            ec ('');
        }
        
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
        $couponList= $Coupon->getList(['time'=>false]);
        
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