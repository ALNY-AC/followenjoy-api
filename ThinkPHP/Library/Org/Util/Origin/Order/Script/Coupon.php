<?php

class Coupon extends OrderComponent{
    
    //启动方法
    public function start(){
        
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
    
    
}