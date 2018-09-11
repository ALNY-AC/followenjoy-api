<?php

namespace Admin\Model;
use Think\Model;
class OrderModel extends Model {
    
    
    public function _initialize (){}
    
    // 创建请求号
    public function creatPrintData(){
        $Download=D('Download');
        
        //创建请求号
        $download_id=getMd5('download');
        //需要条件查询
        
        $start_time=I('start_time');
        $end_time=I('end_time');
        $state=I('state');
        $supplier_id=I('supplier_id');
        
        $where=[];
        if($state!='all'){
            $where['state']=$state;
        }
        
        $where['supplier_id']=['exp','IS NOT NULL'];
        $where['add_time'] = [['gt',$start_time],['lt',$end_time]];
        
        $orders=$this->where($where)->select();
        
        $arr=[];
        foreach ($orders as $key => $order) {
            $item=[];
            $item['order_id']=$order['order_id'];
            $item['download_id']=$download_id;
            $arr[]=$item;
        }
        $Download->addAll($arr);
        return $download_id;
        
        
    }
    
    public function printData($download_id){
        $Download=D('Download');
        
        // ===================================================================================
        // 取出下载数据
        $where=[];
        $where['download_id']=$download_id;
        
        $downloads=  $Download->where($where)->select();
        
        if(!$downloads){
            ec('未找到订单');
            die;
        }
        
        $order_ids=[];
        foreach ($downloads as $key => $id) {
            $order_ids[]=$id['order_id'];
        }
        
        //删除下载数据
        $Download->where($where)->delete();
        
        // ===================================================================================
        // 创建模型
        $Supplier=D('Supplier');// 供货商
        $Goods=D('Goods');// 商品
        $Sku=D('Sku');// Sku
        $Snapshot=D('Snapshot');//快照
        $OrderCoupon=D('OrderCoupon');//优惠券订单关联表
        $OrderAddress=D('OrderAddress');//收货地址库
        
        // ===================================================================================
        // 表格基本数据
        if(!$order_ids){
            ec('没有订单');
            die;
        }
        $where=[];
        $where['order_id']=['in',$order_ids];
        $list=$this->where($where)->select();
        
        $list=toTime($list);
        
        $header=[
        '订单号',
        // '订单交易快照id',
        '分享人id',
        '订单买家id',
        // '订单地址id',
        '订单金额',
        '订单状态',
        '订单状态信息',
        '订单支付号',
        // '订单供货商id',
        '订单创建时间',
        // ==== 供货商信息
        '供货商名',
        // '供货商公司名',
        // '供货商公司电话',
        // '供货商省份',
        // '供货商城市',
        // '供货商区县',
        // '供货商详细地址',
        // '供货商开户账户',
        // '供货商开户银行',
        // '供货商联系人信息',
        // '供货商联系人姓名',
        // '供货商固定电话',
        // '供货商手机',
        // '供货商邮件',
        // '供货商qq',
        // '供货商微信',
        // === 商品数据
        '商品ID',
        '商品标题',
        'SKU编码',
        '商品规格1',
        '商品规格2',
        '商品规格3',
        '商品税率',
        '商品采购价',
        '商品数量',
        '代入计算量',
        '总数量',
        '商品单价',
        // === 收货地址
        '收货人联系电话',
        '省',
        '城市',
        '区县',
        '详细地址',
        // '编号',
        '身份证号',
        '收货人姓名',
        ];
        // dump($header);
        
        // ec($header);
        
        // ===================================================================================
        // 先对数据进行处理再输出
        
        foreach ($list as $key => $value) {
            
            unset($value['edit_time']);
            
            // ===================================================================================
            // 处理基本订单数据
            // 转换状态
            $state_label='';
            $state=$value['state'];
            
            if($state=='1'){
                $state_label='等待用户付款';
            }
            if($state=='2'){
                $state_label='用户已支付，等待发货';
            }
            if($state=='3'){
                $state_label='已发货，等待用户收货';
            }
            if($state=='4'){
                $state_label='包裹已签收';
            }
            if($state=='5'){
                $state_label='退款/售后';
            }
            if($state=='6'){
                $state_label='交易关闭';
            }
            if($state=='7'){
                $state_label='已退款';
            }
            if($state=='8'){
                $state_label='退款失败';
            }
            $offset=getIndex($value,'state');
            array_insert($value,$offset+1,['state_label'=>$state_label]);
            
            
            // ===================================================================================
            // 获得供货商信息
            $supplier_id=$value['supplier_id'];
            $where=[];
            $where['supplier_id']=$supplier_id;
            $supplier=$Supplier
            ->field([
            'supplier_name',//供货商名
            // 'company_name',//公司名
            // 'company_tel',//公司电话
            // 'company_province',//省份
            // 'company_city',//城市
            // 'company_county',//区县
            // 'company_address_detail',//详细地址
            // 'bank_account',//开户账户
            // 'bank_name',//开户银行
            // 'bank_contacts',//联系人信息
            // 'name',//联系人姓名
            // 'telephone',//固定电话
            // 'phone',//手机
            // 'email',//邮件
            // 'qq',//qq
            // 'weixin',//微信
            ])
            ->where($where)
            ->find();
            
            // 插入到数据中
            foreach ($supplier as $j => $s) {
                $s=!$s===""?'--':$s;
                $value[$j]=$s;
            }
            
            
            // ===================================================================================
            // 快照数据
            $snapshot_id=$value['snapshot_id'];
            $where=[];
            $where['snapshot_id']=$snapshot_id;
            $snapshot=$Snapshot
            ->field([
            'goods_id',// 商品号
            'goods_title',// 商品标题
            'shop_code',// 商家自定义SKU编码
            's1',// 规格1
            's2',// 规格2
            's3',// 规格3
            'tax',// 税率
            'purchase_price',// 采购价
            'price',// 单价
            'count',// 数量
            'amount',// 代入计算量
            ])
            ->where($where)
            ->find();
            // 插入到数据中
            
            // getIndex
            // array_insert
            $snapshot['count_amount']=$snapshot['count'];
            if($snapshot['amount']){
                $snapshot['count_amount']=$snapshot['count']*$snapshot['amount'];
            }
            
            $snapshot['goods_price']=$snapshot['price'];
            // $snapshot['tax']=$snapshot['tax']/100;
            unset($snapshot['price']);
            foreach ($snapshot as $j => $s) {
                $s=!$s===""?'--':$s;
                $value[$j]=$s;
            }
            
            // ===================================================================================
            // 收货地址
            
            $address_id=$value['address_id'];
            $where=[];
            $where['address_id']=$address_id;
            $address=$OrderAddress
            ->field([
            'name',//收货人姓名
            'tel',//收货人联系电话
            'province',//省
            'city',//城市
            'county',//区县
            'address_detail',//详细地址
            'id_card',//身份证号
            // 'area_code',//编号
            ])
            ->where($where)
            ->find();
            
            $address['address_name']=$address['name'];
            unset($address['name']);
            foreach ($address as $j => $s) {
                $s=!$s===""?'--':$s;
                $value[$j]=$s;
            }
            
            foreach ($value as $a => $b) {
                $b=''."$b".'';
                $value[$a]=$b;
            }
            unset($value['snapshot_id']);
            unset($value['address_id']);
            unset($value['supplier_id']);
            $list[$key]=$value;
            foreach ($list[$key] as $x => $z) {
                $list[$key][$x] = $list[$key][$x].' ';
            }
            
        }
        
        
        
        // dump($list);
        // die;
        array_unshift($list,$header);
        $fileName="订单列表";
        create_xls($list,$fileName);
        
    }
    
    public function getList($data){
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        // ===================================================================================
        // 创建模型
        $Snapshot=D('Snapshot');
        $OrderAddress=D('OrderAddress');
        
        // ===================================================================================
        // 找订单数据
        $orders=$this
        ->where($where)
        ->order('edit_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        // ===================================================================================
        // 开始组建数据
        foreach ($orders as $key => $order) {
            $order_id=$order['order_id'];
            $pay_id=$order['pay_id'];
            $supplier_id=$order['supplier_id'];
            $user_id=$order['user_id'];
            
            // ===================================================================================
            // 取得快照数据
            $order['snapshot']=$this->getSnapshotInfo($order_id);
            
            // ===================================================================================
            // 取得物流数据
            $order['logistics']=$this->getLogisticsInfo($order_id);
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
            // 取得收货地址数据
            $order['address']=$this->getAddressInfo($order_id);
            
            // ===================================================================================
            // 取得经销商信息
            $order['supplier']=$this->getSupplierInfo($supplier_id);
            
            // ===================================================================================
            // 取得经销商信息
            $order['user']=$this->getUserInfo($user_id);
            
            $orders[$key]=$order;
        }
        
        $orders=toTime($orders);
        
        return $orders;
    }
    
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
    
    //取得订单的经销商数据
    public function getSupplierInfo($supplier_id){
        $Supplier=D('Supplier');
        $where=[];
        $where['supplier_id']=$supplier_id;
        return $Supplier->where($where)->find();
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
        return toTime([$Pay->where($where)->find()])[0];
    }
    //取得订单的收货地址数据
    public function getAddressInfo($address_id){
        $OrderAddress=D('OrderAddress');//收货地址
        $where=[];
        $where['address_id']=$address_id;
        return $OrderAddress->where($where)->find();
    }
    //取得订单的用户数据
    public function getUserInfo($user_id){
        $User=D('User');//用户
        $where=[];
        $where['user_id']=$user_id;
        return toTime([$User->where($where)->find()])[0];
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
        
        return toTime([$afterSale])[0];
    }
    public function get($order_id){
        
        
        // ===================================================================================
        // 查询订单
        $where=[];
        $where['order_id']=$order_id;
        $order=$this
        ->where($where)
        ->find();
        
        if(!$order){
            return null;
        }
        
        $order_id=$order['order_id'];
        $pay_id=$order['pay_id'];
        $supplier_id=$order['supplier_id'];
        $user_id=$order['user_id'];
        
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
        
        // ===================================================================================
        // 取得经销商信息
        $order['supplier']=$this->getSupplierInfo($supplier_id);
        
        // ===================================================================================
        // 取得经销商信息
        $order['user']=$this->getUserInfo($user_id);
        
        // ===================================================================================
        // 取得维权数据
        $order['afterSale']=$this->getAfterSaleInfo($order_id);
        
        $order=toTime([$order])[0];
        return $order;
        
    }
    
    public function saveData($order_id,$save){
        $where=[];
        $where['order_id']=$order_id;
        $save['edit_time']=time();
        
        return $this->where($where)->save($save);
        
        
    }
    
    // 修改单个订单价格，并且重新计算支付单
    public function saveOrderPrice($order_id,$price){
        // ===================================================================================
        // 创建模型
        $Pay=D("Pay");
        $Snapshot=D('Snapshot');//快照
        
        
    }
    
    public function del($ids){
        
        /**
        * 要删除的：
        *      支付单
        *      交易快照
        *      订单
        *      售后单
        *
        */
        
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');//支付单
        $Snapshot=D('Snapshot');//交易快照
        $AfterSale=D('AfterSale');//售后单
        $AfterSale_img=D('AfterSale_img');//售后单图片
        
        // ===================================================================================
        // 先找到订单数据
        $where=[];
        $where['order_id']=['in',$ids];
        $orderList=$this->where($where)->select();
        
        // ===================================================================================
        // 取出支付单id
        $payIds=[];
        
        foreach ($orderList as $k => $v) {
            $payIds[]=$v['pay_id'];
        }
        
        // ===================================================================================
        // 删除支付单
        $where=[];
        $where['pay_id']=['in',$payIds];
        $Pay->where($where)->delete();
        
        // ===================================================================================
        // 删除订单
        $where=[];
        $where['order_id']=['in',$ids];
        $this->where($where)->delete();
        
        // ===================================================================================
        // 删除快照
        $Snapshot->where($where)->delete();
        
        // ===================================================================================
        // 找到售后单ID
        $where=[];
        $where['order_id']=['in',$ids];
        $afterSaleList= $AfterSale->field('after_sale_id')->where($where)->select();
        
        $after_sale_ids=[];
        foreach ($afterSaleList as $k => $v) {
            $after_sale_ids[]=$v['after_sale_id'];
        }
        
        // ===================================================================================
        // 删除售后单和售后图片
        if($after_sale_ids){
            $where=[];
            $where['after_sale_id']=['in',$after_sale_ids];
            $AfterSale->where($where)->delete();//售后单
            $AfterSale_img->where($where)->delete();//售后图片
        }
        
        return true;
    }
    
    
}