<?php
include_once 'Component.php';
class TimeActivity extends Component{
    
    
    public function correct($goods){
        
        
        
        // ===================================================================================
        // 创建模型
        
        $Sku=D('Sku');
        
        
        
        // ===================================================================================
        // 检查时间轴
        
        $goods_id=$goods->getGoodsId();
        $sku_id=$goods->getSkuId();
        $TimeGoods=D('TimeGoods');
        $where=[];
        $where['goods_id']=$goods_id;
        $timeGoods=  $TimeGoods->where($where)->find();
        // ===================================================================================
        // 如果当前时间是营销活动的时间，就修正价格
        $start_time=$timeGoods['start_time'];
        $end_time=$timeGoods['end_time'];
        $to_time=time();
        
        if($to_time>$start_time && $to_time<$end_time){
            
            // ===================================================================================
            // 取得优惠信息
            $where=[];
            $where['sku_id']=$sku_id;
            $activity_price=$Sku->where($where)->getField('activity_price');
            
            // ===================================================================================
            // 添加活动信息
            $order=$this->getSuper();
            $activityInfo=[];
            $activityInfo['activityTitle']='限时抢购';
            $activityInfo['activityPrice']=$activity_price;
            $activityInfo['originPrice']=$goods->getPrice();
            $order->setActivityInfo($activityInfo);
            
            // ===================================================================================
            // 设置商品价格
            $goods->setPrice($activity_price);
            
        }
        // dump($goods->getSkuId());
        
    }
    
}