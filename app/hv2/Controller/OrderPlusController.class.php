<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月24日12:55:55
* 最新修改时间：2018年7月24日12:55:55
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####订单加强版#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class OrderPlusController extends CommonController{
    
    /**
    * 统一订单信息生成接口
    */
    public function getPreOrderInfo(){
        
        Vendor('Origin.Order.Order');
        Vendor('Origin.Order.Goods');
        Vendor('Origin.Order.Components.TimeActivity');//时间轴营销组件
        
        $snapshot_ids=I('snapshot_id');
        dump($snapshot_ids);
        
        $Snapshot=D('Snapshot');
        $where=[];
        $where['snapshot_id']=['in',getIds($snapshot_ids)];
        $snapshotList=$Snapshot
        ->where($where)
        ->select();
        $arr=[];
        foreach ($snapshotList as $k => $v) {
            
            $order=new \Order();
            $goods=new \Goods();
            $timeActivity=new \TimeActivity();
            $order->setGoods($goods);
            
            $goods->setImg($v['img']);//设置图片
            $goods->setTitle($v['goods_title']);//设置标题
            $goods->setSkuInfo($this->getSkuInfo($v));//设置sku的信息
            $goods->setNum($v['count']);//设置数量
            $goods->setPrice($v['price']);//设置单价
            $goods->setSkuId($v['sku_id']);//设置要购买的skuid
            $goods->setGoodsId($v['goods_id']);//设置要购买的商品id
            // ===================================================================================
            // 参加的活动信息
            $order->addComponent($timeActivity);
            
            $arr[]=$order->getPreOrderInfo();// 取得信息，加入数组
        }
        dump($arr);
    }
    
    
    public function getUnpaidCount(){
        
        $state='0';
        
        // ===================================================================================
        // 创建模型
        $Order=D('Order');
        $Pay=D('Pay');
        
        $where=[];
        $where['state']=$state;
        $where['user_id']=session('user_id');
        
        $res=[];
        $res['total']=$Pay->where($where)->count();
        $res['res']=1;
        $res['msg']=$result;
        echo json_encode($res);
        
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
    
    public function getList(){
        $state=I('state').'';
        
        $Order=D('Order');
        
        $page=I('page')?I('page'):1;
        $page_size=I('page_size')?I('page_size'):5;
        
        if($state!='0'){
            $where=[];
            $where['state']=$state;
        }else{
            $where['state']=['NEQ','1'];
        }
        $where['user_id']=session('user_id');
        
        $res['total']=$Order->where($where)->count();
        
        $orderList  =  $Order
        ->order('add_time desc')
        ->where($where)
        ->field('')
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        foreach ($orderList as $k => $v) {
            // goodsInfo
            $v['goodsInfo']=$this->getGoods($v['order_id']);
            $v['logistics']=$this->getLogisticsInfo($v['order_id']);
            $v['buyType']='买';
            $v['stateText']=$this->getStateText($v['state']);
            $v['stateText']=$this->getStateText($v['state']);
            $v['afterSale']=$this->getAfterSaleInfo($v['order_id']);
            
            $orderList[$k]=$v;
        }
        
        $orderList=toTime($orderList);
        
        if($orderList){
            $res['res']=count($orderList);
            $res['msg']=$orderList;
        }else{
            $res['res']=-1;
            $res['msg']=$orderList;
        }
        echo json_encode($res);
    }
    
    
    //取得未支付的订单
    public function getListUnpaid(){
        $state='0';
        
        // ===================================================================================
        // 创建模型
        $Order=D('Order');
        $Pay=D('Pay');
        $page=I('page')?I('page'):1;
        $page_size=I('page_size')?I('page_size'):5;
        
        $where=[];
        $where['state']=$state;
        $where['user_id']=session('user_id');
        $payList  =  $Pay
        ->order('add_time desc')
        ->where($where)
        ->field('')
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $res['total']=$Pay->where($where)->count();
        
        foreach ($payList as $k => $v) {
            
            // ===================================================================================
            // 取订单信息
            $v['orderList']=$this->getOrderList($v['pay_id']);
            
            $v['add_time_text']=date('Y-m-d H:i:s',$v['add_time']);
            
            $payList[$k]=$v;
            
        }
        
        
        if($payList){
            $res['res']=count($payList);
            $res['msg']=$payList;
        }else{
            $res['res']=-1;
            $res['msg']=$payList;
        }
        
        echo json_encode($res);
        
    }
    
    // 给支付单用的
    private function getOrderList($pay_id){
        $Order=D('Order');
        
        
        $where=[];
        $where['pay_id']=$pay_id;
        
        $orderList=$Order->where($where)->select();
        
        
        
        foreach ($orderList as $k => $v) {
            // goodsInfo
            $v['goodsInfo']=$this->getGoods($v['order_id']);
            $v['logistics']=$this->getLogisticsInfo($v['order_id']);
            $v['buyType']='买';
            $v['stateText']=$this->getStateText($v['state']);
            $orderList[$k]=$v;
        }
        
        return $orderList;
        
    }
    
    private function getStateText($k){
        $state=[
        "",
        "待付款",
        "待发货",
        "待收货",
        "交易成功",
        "退款/退货",
        "交易关闭",
        "已退款",
        "退款失败"
        ];
        
        return $state[$k];
        
    }
    
    private function getGoods($order_id){
        $Snapshot=D('Snapshot');
        $where=[];
        $where['order_id']=$order_id;
        $data=$Snapshot
        ->field()
        ->where($where)
        ->find();
        $data['skuInfo']=$this->getSkuInfo($data);
        return $data;
    }
    
    //取得订单的物流数据
    public function getLogisticsInfo($order_id){
        $Logistics=D('Logistics');//物流信息表模型，包括运费
        $where=[];
        $where['order_id']=$order_id;
        return $Logistics->where($where)->find();
    }
    
    /**
    * 组装sku信息
    */
    private function getSkuInfo($skus=[]){
        $info='';
        for ($i=1; $i <=3 ; $i++) {
            if($skus['s'.$i]){
                $info.=$skus['s'.$i];
                if($skus['s'.($i+1)]){
                    $info.=' - ';
                }
            }
        }
        return $info;
    }
    
    
}