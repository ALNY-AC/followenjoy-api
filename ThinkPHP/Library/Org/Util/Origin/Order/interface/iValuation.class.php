<?php
/**
* 计价接口
*/
interface iValuation {
    
    // ===================================================================================
    // 计价钩子函数
    
    /**
    * 计价开始前调用
    * 在计价开始前调用，此时还没有订单总价，任何计价都没有开始。
    */
    public function valuationStart();
    
    
    /**
    * 计价钩子函数
    * 当计价发生时，调用该函数
    * @param Float total 订单总价，没有经过处理的订单总价，即商品单价*商品数量
    */
    public function valuation($total);
    
    
    /**
    * 计价结束后调用
    * 当所有计价完毕后，会调用此函数。
    * @param Float total 订单原价
    * @param Float payMoney 支付单总价
    */
    public function valuationEnd($total,$payMoney);
    
    
}