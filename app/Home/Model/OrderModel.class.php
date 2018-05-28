<?php
namespace Home\Model;
use Think\Model;
class OrderModel extends Model {
    
    
    public function _initialize (){}
    
    
    //物流模板
    public function getFirstPrice($freight,$address){
        
        $area_code=$address['area_code'];//用户选择的区域数据
        
        // 转换区域的数据
        foreach ($freight['areas'] as $key => $value) {
            $value['area_info']=json_decode($value['area'],true);
            $freight['areas'][$key]=$value;
        }
        
        $first_price=-1;
        
        $a1=substr($area_code,0,2);
        $a2=substr($area_code,2,2);
        $a3=substr($area_code,4,2);
        $isStop=false;
        
        foreach ($freight['areas'] as $key => $area) {
            $area_info=$area['area_info'];
            if($isStop){
                return $first_price ;
            }
            foreach ($area_info as $key => $info) {
                $value=$info['value'];
                
                if(strpos($value,$a1.$a2.$a3)!==false){
                    //找区县
                    $first_price=$area['first_price'];
                    $isStop = true;
                }else{
                    //没有区县，找市
                    
                    if(strpos($value,$a1.$a2.'00')!==false){
                        $first_price=$area['first_price'];
                        $isStop = true;
                    }else{
                        //没有市，找省份
                        if(strpos($value,$a1.'0000')!==false){
                            $first_price=$area['first_price'];
                        }
                        
                    }
                    
                }
            }
        }
        return $first_price ;
        
        
    }
    
    
    //创建订单数据
    public function createOrder($snapshot,$address,$pay_id){
        
        $Goods=D('Goods');//商品模型
        $Snapshot=M('Snapshot');//快照模型
        $Logistics=M('Logistics');//物流信息表模型
        $Activity=D('Activity');//活动模型
        $Coupon=D('Coupon');//优惠券模型
        $Sku=D('Sku');//Sku数据
        $Freight=D('Freight');//物流模板
        
        
        // ===================================================================================
        // 基本数据
        $user_id=session('user_id');
        $data=[];
        $data['orderData']=null;
        $data['logisticsData']=null;
        
        // ===================================================================================
        // 基本数据
        $snapshot_id=$snapshot['snapshot_id'];//快照id
        $goods_id=$snapshot['goods_id'];//商品id
        $order_id=date('YmdHis',time()).rand(10000,99999);//创建订单号
        $activity_id=$snapshot['activity_id'];//促销活动的id
        $sku_id=$snapshot['sku_id'];//sku 的id
        
        // ===================================================================================
        // 找商品信息
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$Goods->where($where)->find();
        
        // ===================================================================================
        // 取sku数据
        $where=[];
        $where['sku_id']=$sku_id;
        $sku=$Sku->where($where)->find();
        
        // ===================================================================================
        // 找物流信息
        
        $freight_id=$goods['freight_id'];
        $freight=$Freight->get($freight_id);
        
        // 取得物流价格
        $first_price = $this->getFirstPrice($freight,$address);
        
        // ===================================================================================
        // 创建物流表
        $logistics=[];
        $logistics_id=getMd5('logistics');//物流信息id
        $logistics['logistics_id']=$logistics_id;//物流信息id
        $logistics['price']=$first_price;//邮费
        $logistics['freight_id']=$freight_id;//运费模板id
        $logistics['order_id']=$order_id;//订单号
        $logistics['logistics_number']='';//物流号
        $logistics['type']='';//物流类型，圆通、中通等
        $logistics['add_time']=time();//添加时间
        $logistics['edit_time']=time();//编辑时间
        
        $data['logisticsData']=$logistics;
        
        // ===================================================================================
        // 这里进入计价环节
        $discount=0;//优惠的价格
        
        // ===================================================================================
        //找促销活动信息
        $discount+=$Activity->getActivityPrice($activity_id,$snapshot_id,$order_id);
        
        // ===================================================================================
        // 计算价格
        $price=$snapshot['price']*$snapshot['count']-$discount;
        $price+=$first_price;
        
        // ===================================================================================
        // 组装订单数据
        $orderData=[];//订单数据
        $orderData['order_id']=$order_id;//订单号
        $orderData['snapshot_id']=$snapshot_id;//快照id
        $orderData['user_id']=$user_id;//买家id
        $orderData['share_id']=$snapshot['share_id'];//分享人id
        $orderData['address_id']=$address['address_id'];//地址库id
        $orderData['price']=$price;//应付金额（计算商品总价且优惠后的价格）
        $orderData['state']=1;//状态，默认是1
        $orderData['pay_id']=$pay_id;//支付号
        $orderData['supplier_id']=$sku['supplier_id'];//供货商id，取此时商品sku设置的数据，留空表示平台发货
        $orderData['add_time']=time();//添加时间
        $orderData['edit_time']=time();//编辑时间
        $data['orderData']=$orderData;
        
        // ===================================================================================
        // 设置快照的 order_id
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $save=[];
        $save['order_id']=$order_id;
        $Snapshot->where($where)->save($save);
        
        return $data;
    }
    
    public function create($data){
        
        
        $isDebug=false;
        
        // $order_info_id=date('YmdHis',time()).rand(10000,99999);
        // ===================================================================================
        // 创建支付号，全局使用
        $pay_id=date('YmdHis',time()).rand(10000,99999);
        // ===================================================================================
        // 用户数据
        $user_id=session('user_id');
        
        // ===================================================================================
        // 实际需要支付的金额
        $total=0;
        
        // ===================================================================================
        // 获得基本数据
        $pay_type=$data['pay_type'];//支付方式
        $address_id=$data['address_id'];//地址id
        $snapshot_ids=$data['snapshot_id'];//快照id数组
        $coupon_id=$data['coupon_id'];//优惠券
        // share_id
        
        // ===================================================================================
        // 创建模型
        $Sku=M('Sku');//sku模型
        $Address=D('Address');//用户地址库模型s
        $Bag=D('Bag');//购物袋模型
        $Activity=D('Activity');//活动模型
        $Coupon=D('Coupon');//优惠券模型
        $Pay=D('Pay');//支付单模型
        $OrderAddress=M('OrderAddress');//地址库模型
        $Snapshot=M('Snapshot');//快照模型
        $Order=M('Order');//订单模型
        $Logistics=M('Logistics');//物流信息表模型
        $OrderCoupon=D('OrderCoupon');//优惠券订单关联表
        
        
        // 测试环境
        if($isDebug){
            // $OrderAddress->where('1=1')->delete();
            // $save=[];
            // $save['order_id']=null;
            // $Snapshot->where('1=1')->save($save);
            // $Order->where('1=1')->delete();
            // $Logistics->where('1=1')->delete();
            // $Pay->where('1=1')->delete();
            // $OrderCoupon->where('1=1')->delete();
        }
        // ===================================================================================
        // 找到所有的快照数据
        $where=[];
        $where['snapshot_id']=['in',$snapshot_ids];
        $snapshots=$Snapshot->where($where)->select();
        
        // ===================================================================================
        // 找地址信息
        $where=[];
        $where['user_id']=$user_id;
        $where['address_id']=$address_id;
        $address=$Address->where($where)->find();
        
        //创建新数据
        $address_id=getMd5('address');
        $address['address_id']=$address_id;
        $address['add_time']=time();
        $address['edit_time']=time();
        
        // ===================================================================================
        // 循环遍历快照，然后创建订单
        $orderDatas=[];
        $logisticsDatas=[];
        
        foreach ($snapshots as $key => $snapshot) {
            $data=$this->createOrder($snapshot,$address,$pay_id);// 创建订单数据
            $orderDatas[]=$data['orderData'];// 订单数据添加到数组中
            $logisticsDatas[]=$data['logisticsData'];// 物流数据添加到数组中
            $total+=$data['orderData']['price'];// 计算总价
            $snapshots[$key]['order_id']=$data['orderData']['order_id'];
        }
        
        
        // ===================================================================================
        //找优惠券信息，如果优惠券可用，在本次使用后优惠券失效，此次订单只可以使用一次。
        $coupon=$Coupon->getCouponPrice($coupon_id,$orderDatas,$snapshots,$total);
        
        $total-=$coupon['price'];
        // ===================================================================================
        // 创建支付单
        $payData=[];
        $payData['pay_id']=$pay_id;// 支付号
        $payData['user_id']=$user_id;// 买家id
        $payData['price']=$total;// 需要支付的金额，已经优惠后的价格，实际需要支付的价格
        $payData['state']=0;//支付状态,0：未支付，1：已支付
        $payData['pay_type']=$pay_type;//支付类型，1：支付宝支付，2：微信支付，3：余额支付
        $payData['add_time']=time();
        $payData['edit_time']=time();
        
        
        // ===================================================================================
        // 写入到数据库中
        $Order->addAll($orderDatas);//添加订单数据
        $OrderAddress->add($address);//添加收货地址信息
        $Pay->add($payData);//添加支付单数据
        $Logistics->addAll($logisticsDatas);//添加物流信息表
        
        // ec('优惠券数据');
        // dump($coupon);
        // ec('订单数据');
        // dump($orderDatas);
        // ec('地址数据');
        // dump($address);
        // ec('支付数据');
        // dump($payData);
        // ec('物流数据');
        // dump($logisticsDatas);
        // die;
        
        //创建完成，删除购物车数据
        if(!$isDebug){
            $where=[];
            $where['snapshot_id']=['in',$snapshot_ids];
            $Bag->where($where)->delete();
        }
        
        return $pay_id;  // 返回 pay_id
        
    }
    
    //组成支付数据
    public function pay($pay_id){
        
        
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');
        //取得数据
        
        $where=[];
        $where['pay_id']=$pay_id;
        $pay=$Pay->where($where)->find();
        $pay_type=$pay['pay_type'];
        // ===================================================================================
        // 判断类型
        if($pay_type=='3'){
            // 余额支付
            //然后根据类型调用不同的支付接口
            
            return $this->balancePayment($pay_id);
        }
        
        if($pay_type=='2'){
            // 微信支付
        }
        
        if($pay_type=='1'){
            // 支付宝
        }
        
    }
    
    //余额支付
    public function balancePayment($pay_id){
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');
        $User=D('User');
        
        // ===================================================================================
        // 取得支付数据
        $where=[];
        $where['pay_id']=$pay_id;
        $pay=$Pay->where($where)->find();
        // ===================================================================================
        // 取得订单金额
        $price=$pay['price'];
        // fission
        
        // ===================================================================================
        // 减去用户的余额
        $where=[];
        $where['user_id']=session('user_id');
        $User->where($where)->setDec('user_money',$price);
        
        // ===================================================================================
        // 分销处理
        
        // ===================================================================================
        // 设置订单状态
        // 1、待付款
        // 2、待发货
        // 3、待收货
        // 4、交易成功
        // 5、退款/退货
        // 6、已取消
        $save=[];
        $save['state']=2;// 待发货
        $where=[];
        $where['user_id']=session('user_id');
        $where['pay_id']=$pay_id;
        $this->where($where)->save($save);
        
        // ===================================================================================
        // 设置支付单状态
        // 0：未支付
        // 1：已支付
        // 2：已取消
        $save=[];
        $save['state']=1;
        $where=[];
        $where['user_id']=session('user_id');
        $where['pay_id']=$pay_id;
        $Pay->where($where)->save($save);
        
        
        // 减库存
        $where=[];
        $where['pay_id']=$pay_id;
        $Order=D('Order');
        $order=$Order->where($where)->select();
        $orderIds=[];
        foreach ($order as $k => $v) {
            $orderIds[]=$v['order_id'];
        }
        
        $Snapshot=D('Snapshot');
        
        $where=[];
        $where['order_id']=['in',$orderIds];
        
        $snapshot=$Snapshot->where($where)->select();
        
        $Sku=D('Sku');
        
        foreach ($snapshot as $k => $v) {
            $count=$v['count'];
            $sku_id=$v['sku_id'];
            $where=[];
            $where['sku_id']=$sku_id;
            $Sku->where($where)->setDec('stock_num',$count);
        }
        
        $Vip=D('Vip');
        $Vip->销售佣金奖($pay_id);
        
        return true;
    }
    
    public function getList(){
        
        // ===================================================================================
        // 基本数据
        $user_id=session('user_id');
        
        
        // ===================================================================================
        // 取得当前用户订单数据
        $where=[];
        $where['user_id']=$user_id;
        $orders=$this->where($where)->select();
        
        
        // ===================================================================================
        // 开始组建数据
        foreach ($orders as $key => $order) {
            $order_id=$order['order_id'];
            $pay_id=$order['pay_id'];
            
            // ===================================================================================
            // 取得快照数据
            $order['snapshot']=$this->getSnapshotInfo($order_id);
            
            // ===================================================================================
            // 取得物流数据
            $order['logistics']=$this->getLogisticsInfo($order_id);
            
            // ===================================================================================
            // 取得支付单数据
            $order['pay']=$this->getPayInfo($pay_id);
            
            // ===================================================================================
            // 取得促销活动信息
            
            // ===================================================================================
            // 取得收货地址数据
            $order['address']=$this->getAddressInfo($order['address_id']);
            
            $orders[$key]=$order;
        }
        
        
        $orders=toTime($orders);
        return $orders;
        
    }
    
    //取得订单的快照数据
    public function getSnapshotInfo($order_id){
        $Snapshot=D('Snapshot');//快照
        $where=[];
        $where['order_id']=$order_id;
        return $Snapshot->where($where)->find();
    }
    
    //取得订单的物流数据
    public function getLogisticsInfo($order_id){
        $Logistics=D('Logistics');//物流信息表模型，包括运费
        $where=[];
        $where['order_id']=$order_id;
        return $Logistics->where($where)->find();
    }
    //取得订单的支付单数据
    public function getPayInfo($pay_id){
        $Pay=D('Pay');//支付单
        $where=[];
        $where['pay_id']=$pay_id;
        return $Pay->where($where)->find();
    }
    //取得订单的收货地址数据
    public function getAddressInfo($address_id){
        $OrderAddress=D('OrderAddress');//收货地址
        $where=[];
        $where['address_id']=$address_id;
        return $OrderAddress->where($where)->find();
    }
    
    
    //取得维权数据
    public function getAfterSaleInfo($order_id){
        $AfterSale=D('AfterSale');
        $AfterSaleImg=D('AfterSaleImg');
        
        $where=[];
        $where['order_id']=$order_id;
        $afterSale= $AfterSale->where($where)->find();
        
        $where=[];
        $where['after_sale_id']=$afterSale['after_sale_id'];
        $afterSale['img_list']= $AfterSaleImg->where($where)->select();
        
        return $afterSale;
    }
    
    //通过订单详情id获取单个订单信息
    public function get($order_id){
        
        $user_id=session('user_id');
        $where=[];
        $where['user_id']=$user_id;
        $where['order_id']=$order_id;
        
        
        
        // ===================================================================================
        // 创建模型
        $Snapshot=D('Snapshot');
        $Logistics=D('Logistics');
        $Pay=D('Pay');
        $AfterSale=D('AfterSale');
        $OrderAddress=D('OrderAddress');
        
        
        
        // ===================================================================================
        // 取订单信息
        $where=[];
        $where['order_id']=$order_id;
        $order=$this->where($where)->find();
        
        // ===================================================================================
        // 取商品信息
        $where=[];
        $where['order_id']=$order_id;
        $snapshot=$Snapshot->where($where)->select();
        
        // ===================================================================================
        // 取物流信息
        $where=[];
        $where['order_id']=$order_id;
        $logistics=$Logistics->where($where)->find();
        
        // ===================================================================================
        // 取支付状态
        $where=[];
        $where['pay_id']=$order['pay_id'];
        $pay=$Pay->where($where)->find();
        
        // ===================================================================================
        // 取收货地址
        $where=[];
        $where['address_id']=$order['address_id'];
        $address=$OrderAddress->where($where)->find();
        
        $data=[];
        $data['snapshot']=$snapshot;
        $data['order']=$order;
        $data['logistics']=$logistics;
        $data['pay']=$pay;
        $data['address']=$address;
        return $data;
    }
    
    public function cancel($pay_id){
        //取消订单
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');//支付单模型
        
        $where=[];
        $where['pay_id']=$pay_id;
        
        $save=[];
        $save['state']=6;
        $this->where($where)->save($save);
        
        $save=[];
        $save['state']=2;
        $Pay->where($where)->save($save);
        
        return true;
        
    }
    
}