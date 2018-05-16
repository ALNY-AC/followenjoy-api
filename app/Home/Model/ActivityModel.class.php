<?php
namespace Home\Model;
use Think\Model;
class ActivityModel extends Model {
    
    
    public function _initialize (){}
    
    
    /**
    * 获得促销减去的价格，当促销活动的信息存在，此函数会将促销信息写入到关联表中
    * @param String $activity_id 促销活动的id
    * @param String $snapshot_id 快照id，用于条件判断
    * @param String $order_id 订单id，如果存在，就写入到关联表中，如果为false，就只是取得数据不写入
    
    * @return 返回促销活动可以减去的价格，如果不满足条件就返回0，否则返回可优惠的价格
    */
    
    public function getActivityPrice($activity_id,$snapshot_id,$order_id){
        if(!$activity_id)return 0;// 没有促销id
        // ===================================================================================
        // 创建模型
        
        // ===================================================================================
        // 获得优惠活动的数据
        
        // ===================================================================================
        // 判断促销活动的条件
        
        // ===================================================================================
        // 返回促销可优惠的价格
        
        // ===================================================================================
        // 将促销信息写入到数据库中，如果$order_id存在，且不为false
        
        // ===================================================================================
        // 返回促销信息数据
        return 0;
    }
    
    
}