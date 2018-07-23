<?php
/**
* 自定义扩展订单的组件
* 比如营销活动、优惠券计价等
*/
require_once "interface/iAffair.class.php";
require_once "interface/iValuation.class.php";

abstract class OrderComponent implements iValuation,iAffair {
    
    // ===================================================================================
    // 基本属性
    private $order;//订单实例
    private $components=[];//组件的扩展组件
    private $super;//上级组件
    private $name='';//组件的名字
    
    // ===================================================================================
    // 基本方法
    /**
    * 当组件被插入订单后，且已经关联到订单后调用
    */
    public function start(){
        
    }
    
    // ===================================================================================
    // getter/setter
    public function getOrder(){
        return $this->order;
    }
    
    public function setOrder($order){
        $this->order=$order;
    }
    
    
    public function getSuper(){
        return $this->super;
    }
    
    public function setSuper($super){
        $this->super=$super;
    }
    
    // ===================================================================================
    // 扩展方法
    
    /**
    * 添加一个自定义组件
    */
    public function addComponent($component){
        $this->components[]=$component;
        $component->setOrder($this->getOrder());
        $component->setSuper($this);
        $component->start();
    }
    
    public function getComponents(){
        return $this->components;
    }
    
    
    // ===================================================================================
    // 计价钩子函数
    
    /**
    * 计价开始前调用
    * 在计价开始前调用，此时还没有订单总价，任何计价都没有开始。
    */
    public function valuationStart(){
        
    }
    
    
    /**
    * 计价钩子函数
    * 当计价发生时，调用该函数
    * @param Float total 订单总价，没有经过处理的订单总价，即商品单价*商品数量
    * @param Float devTotal 已经被修正后的订单价格。
    * @return Float 返回修正后的价格。
    */
    public function valuation($total){
        return $total;
    }
    
    
    /**
    * 计价结束后调用
    * 当所有计价完毕后，会调用此函数。
    * @param Float total 原始订单总价
    * @param Float payMoney 支付单总价
    * @return Float payMoney 返回修正后的订单总价
    */
    public function valuationEnd($total,$payMoney){
        return $payMoney;
    }
    
    // ===================================================================================
    // 事务队列
    // 当一切都确定后，开始各个组件自己记录自己的数据。
    /**
    * 事务开始执行前调用。
    * 当事务开始执行前调用。
    */
    public function affairStart(){
        
    }
    
    /**
    * 当事务开始后调用
    */
    public function affair(){
        
    }
    
    /**
    * 当事务结束后调用
    */
    public function affairEnd(){
        
    }
    
    
}