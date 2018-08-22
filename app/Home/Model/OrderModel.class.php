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
    public function createOrder($snapshot,$address,$pay_id,$message){
        
        $Goods=D('Goods');//商品模型
        $Snapshot=M('Snapshot');//快照模型
        $Logistics=M('Logistics');//物流信息表模型
        $Activity=D('Activity');//活动模型
        $Coupon=D('Coupon');//优惠券模型
        $Sku=D('Sku');//Sku数据
        $Freight=D('Freight');//物流模板
        $Time=D('Time');//限时购
        $TimeGoods=D('TimeGoods');//限时购商品
        
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
        $sku_id=$snapshot['sku_id'];//sku 的id
        
        // ===================================================================================
        // 删掉订单中，订单中，没有快照的
        // 1、待付款
        // 2、待发货
        // 3、待收货
        // 4、交易成功
        // 5、退款/退货
        // 6、已关闭
        // 7、已退款
        // 8、退款失败
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $where['state']=1;
        $this->where($where)->delete();
        
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
        //找促销活动信息,如果有，就记录到表中
        // $discount+=$Activity->getActivityPrice($activity_id,$snapshot_id,$order_id,$goods_id);
        // 限时购计价
        
        // ===================================================================================
        // 先看看这个商品在TimeGoods中有没有
        $timeGoods=$TimeGoods->where(['goods_id'=>$goods_id])->find();
        $toTime=time();
        $start_time=$time['start_time'];
        $end_time=$time['end_time'];
        if($toTime>$start_time && $toTime < $end_time){
            $snapshot['original_price']=$snapshot['price'];
            $snapshot['price'] =   $snapshot['activity_price'];
            $snapshot['earn_price'] =   $snapshot['activity_earn_price'];
        }
        
        // ===================================================================================
        // 计算价格
        $price=($snapshot['price']*$snapshot['count']);
        $price+=$first_price;
        // 优惠后的订单总价=订单总价*打折
        
        // ===================================================================================
        // 组装订单数据
        $orderData=[];//订单数据
        
        // ===================================================================================
        // 取出此人的上级店铺号
        
        $UserSuper=D('UserSuper');//限时购商品
        $User=D('User');//用户
        
        $where=[];
        $where['user_id']=$user_id;
        $user_super=$UserSuper->where($where)->getField('super_id');
        if($user_super){
            $where=[];
            $where['user_id']=$user_super;
            $shop_id=$User->where($where)->getField('shop_id');
        }else{
            $shop_id='';
        }
        
        // ===================================================================================
        
        $orderData['order_id']=$order_id;//订单号
        $orderData['snapshot_id']=$snapshot_id;//快照id
        $orderData['share_id']=$snapshot['share_id'];//分享人id
        $orderData['shop_id']=$shop_id;//店铺id
        $orderData['user_id']=$user_id;//买家id
        $orderData['address_id']=$address['address_id'];//地址库id
        $orderData['price']=$price;//应付金额（计算商品总价且优惠后的价格）
        $orderData['state']=1;//状态，默认是1
        $orderData['pay_id']=$pay_id;//支付号
        $orderData['supplier_id']=$sku['supplier_id'];//供货商id，取此时商品sku设置的数据，留空表示平台发货
        $orderData['message']=$message;//添加时间
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
        // ===================================================================================
        // 创建模型
        $Sku=M('Sku');//sku模型
        $Address=D('Address');//用户地址库模型s
        $Bag=D('Bag');//购物袋模型
        $Activity=D('Activity');//活动模型
        $Coupon=D('Coupon');//优惠券模型
        $Pay=D('Pay');//支付单模型
        $OrderAddress=M('OrderAddress');//地址库模型
        $Snapshot=D('Snapshot');//快照模型
        $Order=M('Order');//订单模型
        $Logistics=M('Logistics');//物流信息表模型
        $OrderCoupon=D('OrderCoupon');//优惠券订单关联表
        $User=D('User');//优惠券订单关联表
        
        
        // ===================================================================================
        // 验证余额抵扣
        $balance_pwd=I('balance_pwd');
        
        if($balance_pwd){
            // 验证密码
            $user_id=session('user_id');
            $where=[];
            $where['user_id']=$user_id;
            $pay_code=$User->where($where)->getField('pay_code');
            $user_money=$User->where($where)->getField('user_money');
            $balance_value=I('balance_value');
            if($user_money<=0 || $user_money-$balance_value<0){
                //余额不能用，钱不够了
                $res=[];
                $res['res']=-50;
                echo json_encode($res);
                die;
                $isBalance=false;
            }else{
                //余额能用
                //加密算法： 用户id+密码+密匙
                $balance_pwd=md5($user_id.$balance_pwd.__KEY__);
                if($pay_code==$balance_pwd){
                    $isBalance=true;
                }else{
                    // ===================================================================================
                    // 密码错误
                    $res=[];
                    $res['res']=-51;
                    echo json_encode($res);
                    die;
                    $isBalance=false;
                }
            }
        }else{
            $isBalance=false;
        }
        
        
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
        $message=$data['message'];//买家留言
        // share_id
        
        
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
        
        $isToAppShop='-1';
        
        foreach ($snapshots as $k => $v) {
            $v=$Snapshot->getTime($v);
            $snapshots[$k]=$v;
            if($v['goods_id']=='1469'){
                $isToAppShop='1';
                $v['count']=1;
                $snapshots[$k]=$v;
            }
            
        }
        
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
            $data=$this->createOrder($snapshot,$address,$pay_id,$message);// 创建订单数据
            $orderDatas[]=$data['orderData'];// 订单数据添加到数组中
            $logisticsDatas[]=$data['logisticsData'];// 物流数据添加到数组中
            $total+=$data['orderData']['price'];// 计算总价
            $snapshots[$key]['order_id']=$data['orderData']['order_id'];
        }
        
        // ===================================================================================
        //找优惠券信息，如果优惠券可用，在本次使用后优惠券失效，此次订单只可以使用一次。
        $coupon=$Coupon->getCouponPrice($coupon_id,$orderDatas,$snapshots,$total);
        
        $total-=$coupon['price'];
        if($total<0){
            $total=0;
        }
        
        if($isToAppShop=='1'){
            $total2=0;
            foreach ($snapshots as $k => $v) {
                $total2+=$v['count']*$v['price'];
            }
            if($total2>=60){
                $total-=9.9;
            }
        }
        
        if($isBalance){
            // ===================================================================================
            // 余额抵扣
            
            if($balance_value>$total){
                $balance_value=$total;
            }
            $total-=$balance_value;
            // ===================================================================================
            // 减去用户余额
            $user_id=session('user_id');
            $where=[];
            $where['user_id']=$user_id;
            $User->where($where)->setDec('user_money',$balance_value);
            
        }
        
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
        // 判断是否是0元
        if($total<=0){
            
            $payData['state']=1;//支付状态,0：未支付，1：已支付
            foreach ($orderDatas as $k => $v) {
                $v['state']=2;
                $orderDatas[$k]=$v;
            }
            
        }
        
        // dump($payData);
        // dump($orderDatas);
        // die;
        
        // ===================================================================================
        // 写入到数据库中
        $Order->addAll($orderDatas);//添加订单数据
        $OrderAddress->add($address);//添加收货地址信息
        $Pay->add($payData);//添加支付单数据
        $Logistics->addAll($logisticsDatas);//添加物流信息表
        
        // ===================================================================================
        // 减库存
        $where=[];
        $where['pay_id']=$pay_id;
        $Order=D('Order');
        $order=$Order->where($where)->select();
        $orderIds=[];
        foreach ($order as $k => $v) {
            $orderIds[]=$v['order_id'];
        }
        
        $where=[];
        $where['order_id']=['in',$orderIds];
        
        $orderData=[];
        $orderData['edit_time']=time();
        $Order->where($where)->save($orderData);
        
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
        $orders=$this
        ->order('add_time desc')
        ->where($where)
        ->select();
        
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
            // 2018052309422333997
            // ===================================================================================
            // 检查物流状态
            if($order['state']==3){
                // 3、待收货
                if($this->isLogistics($order_id, $order['logistics']['logistics_id'])){
                    $order['logistics']=$this->getLogisticsInfo($order_id);
                }
            }
            
            // ===================================================================================
            // 取得支付单数据
            $order['pay']=$this->getPayInfo($pay_id);
            
            // ===================================================================================
            // 取得促销活动信息
            
            // ===================================================================================
            // 取得优惠券使用记录
            $order['coupon']=$this->getCouponInfo($order['order_id']);
            
            // ===================================================================================
            // 取得收货地址数据
            $order['address']=$this->getAddressInfo($order['address_id']);
            
            
            // ===================================================================================
            // 取维权
            $order['afterSale']=$this->getAfterSaleInfo($order['order_id']);
            
            // ===================================================================================
            // 判断是否到达十五天，如果是，就要确认收货
            // 先找物流信息
            // $Logistics
            // logistics
            
            $orders[$key]=$order;
        }
        
        $orders=toTime($orders);
        return $orders;
        
    }
    
    public function getCouponInfo($order_id){
        
        $OrderCoupon=D('OrderCoupon');
        $where=[];
        $where['order_id']=$order_id;
        $coupon=$OrderCoupon->field('price')->where($where)->find();
        return $coupon;
    }
    // 2018052006542149913
    
    public function isLogistics($order_id,$logistics_id){
        $Logistics=D('Logistics');//物流信息表模型，包括运费
        $where=[];
        $where['logistics_id']=$logistics_id;
        $logistics=$Logistics->where($where)->find();
        
        if(!$logistics['state']){
            
            $where=[];
            $where['com']=$logistics['logistics_name'];
            $where['num']=$logistics['logistics_number'];
            $info=$Logistics->getInfo($where);
            
            if($info['state']=='3'){
                // 快递已签收
                // ===================================================================================
                // 取出签收时间
                
                $logisticsTime=$info['data'][0]['ftime'];
                
                $logisticsTime=strtotime($logisticsTime);//这是收货时间的时间戳
                $toTime=time();//这是今天的时间戳
                
                // 公式为：
                // 此时此刻的时间>=收货日期+15天
                $time15=strtotime('+15 day',$logisticsTime);//这是确认收货15天后的时间戳
                
                if($toTime>=$time15){
                    // 自动确认收货
                    $this->okLogistics($order_id,$logistics_id);
                    return true;
                }else{
                    return false;
                }
                // $testTime=date('Y-m-d H:i:s',$time15);
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    
    // 自动确认收货
    public function okLogistics($order_id,$logistics_id){
        $Logistics=D('Logistics');//物流信息表模型
        // 设置状态
        $where=[];
        $where['logistics_id']=$logistics_id;
        $save=[];
        $save['state']=1;
        $Logistics->where($where)->save($save);
        // 让订单完成，同时有分润
        $this->okOrder($order_id);
        
    }
    
    
    //取得订单的快照数据
    public function getSnapshotInfo($order_id){
        $Snapshot=D('Snapshot');//快照
        $where=[];
        $where['order_id']=$order_id;
        $data=    $Snapshot->where($where)->find();
        //检查时间轴数据
        $data=$Snapshot->getTime($data);
        return $data;
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
        
        $where=[];
        $where['order_id']=$order_id;
        $afterSale= $AfterSale->where($where)->find();
        
        if(!$afterSale){
            return null;
        }
        $AfterSaleImg=D('AfterSaleImg');
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
        // 取得优惠券使用记录
        $coupon=$this->getCouponInfo($order_id);
        
        // ===================================================================================
        // 取收货地址
        $where=[];
        $where['address_id']=$order['address_id'];
        $address=$OrderAddress->where($where)->find();
        
        $data=[];
        
        // ===================================================================================
        // 如果是售后订单，取得售后信息
        if($order['state']=='8'){
            $AfterSale=D('AfterSale');
            $afterSale=$AfterSale->get($order['order_id'],'order_id');
        }
        
        $data['snapshot']=$snapshot;
        $data['order']=$order;
        $data['logistics']=$logistics;
        $data['pay']=$pay;
        $data['address']=$address;
        $data['afterSale']=$afterSale;
        $data['coupon']=$coupon;
        return $data;
    }
    
    public function cancel($pay_id){
        
        // 取消订单
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
        
        // ===================================================================================
        // 回库存
        
        $where=[];
        $where['pay_id']=$pay_id;
        $Order=D('Order');
        $order=$Order->where($where)->select();
        $orderIds=[];
        foreach ($order as $k => $v) {
            $orderIds[]=$v['order_id'];
        }
        
        $where=[];
        $where['order_id']=['in',$orderIds];
        
        $orderData=[];
        $orderData['edit_time']=time();
        $Order->where($where)->save($orderData);
        
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
            $Sku->where($where)->setInc('stock_num',$count);
        }
        
        
        return true;
    }
    
    public function okOrder($order_id){
        
        // 1、待付款
        // 2、待发货
        // 3、待收货
        // 4、交易成功
        // 5、退款/退货
        // 6、已关闭
        // 7、已退款
        // 8、退款失败
        
        $where=[];
        $where['order_id']=$order_id;
        
        $data=[];
        $data['state']=4;//确认收货
        $result=$this->where($where)->save($data);
        
        // ===================================================================================
        // 佣金结算
        $Vip=D('Vip');
        $Vip->销售佣金奖($order_id);
        
        if($result){
            return true;
        }else{
            return false;
        }
        
    }
    
}