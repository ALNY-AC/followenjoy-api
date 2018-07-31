<?php
/**
* 订单对象
* 一个对象对应一个商品
* 如果是多订单，需要多个对象
*/
class Order {
    
    // ===================================================================================
    // 基本属性
    private $goods;
    private $values=[];
    private $activityInfo=[];
    
    
    private $comps=[];//组件
    
    private $orderId='';//订单号
    private $price=0.00;//订单总额
    private $payId='';
    private $addresId='';
    
    public function Order(){}
    
    // ===================================================================================
    // 基本函数
    
    public function setGoods($goods){
        $this->goods=$goods;
        $this->goods->setOrder($this);
    }
    public function getGoods(){
        return $this->goods;
    }
    
    
    //设置营销活动信息
    public function setActivityInfo($activityInfo=[]){
        $this->activityInfo=$activityInfo;
    }
    
    //取得营销活动的描述信息
    public function getActivityInfo(){
        return $this->activityInfo;
    }
    
    // ===================================================================================
    // 扩展函数
    
    
    // ===================================================================================
    // 业务函数
    /**
    * 获取订单信息
    * 一次获取一个订单信息
    * 根据设置好的参数返回规定的json
    * 此函数并不保存任何数据
    */
    public function getPreOrderInfo(){
        $goods=$this->goods;
        $order=[];
        
        //让组件对单价进行修正
        foreach ($this->comps as $k => $v) {
            $v->correct($goods);
        }
        
        $order['goodsInfo']=$goods->getValues();
        $order['total']=$goods->getNum()*$goods->getPrice();
        $order['activityInfo']=$this->getActivityInfo();
        
        return $order;
    }
    
    // 添加组件
    public function addComponent($comp){
        $this->comps[]=$comp;
        $comp->setSuper($this);
    }
    
}