<?php
/**
* 时间轴
*/
class Time extends OrderComponent{
    
    private $discountPrice=5;//时间轴优惠的价格，此为具体的值，并不是用于减去多少
    
    //启动方法
    public function start(){
        // ec(__METHOD__.'<br>');
    }
    
    /**
    * 计价钩子函数
    * 当计价发生时，调用该函数
    * @param Float total 订单总价，没有经过处理的订单总价，即商品单价*商品数量
    * @param Float devTotal 已经被修正后的订单价格。
    * @return Float 返回修正后的价格。
    */
    public function valuation($total){
        
        $superGoods=$this->getSuper();//取得上级组件：商品
        $title=$superGoods->getTitle();//取得商品的标题（无用）
        $price=$superGoods->getPrice();//取得商品的原价（无用）
        $num=$superGoods->getNum();//取得商品的数量
        
        $originPrice=$price*$num;//原价计算（无用）
        $discountPrice=$this->discountPrice*$num;//优惠价计算
        
        ec($title.'的原价是：'.$originPrice.'￥');
        ec($title.'的优惠价是：'.$discountPrice.'￥');
        
        return $discountPrice;
    }
    
}