<?php

require_once "OrderComponent.class.php";


require_once "User/User.class.php";

require_once "StateMachine/StateInfo.class.php";
require_once "StateMachine/StateMachine.class.php";

require_once "Pay/PayInfo.class.php";
require_once "Pay/Pay.class.php";

require_once "Message/Message.class.php";

require_once "Goods/Goods.class.php";

require_once "Distribution/Logistics.class.php";
require_once "Distribution/Address.class.php";


require_once "Script/Coupon.php";
require_once "Script/Time.php";

class Order {
    
    // ===================================================================================
    // 基本属性
    private $orderId;//订单号
    private $price;//订单总价
    
    // ===================================================================================
    // 基本组件
    private $Goods;//商品组件
    
    // ===================================================================================
    // 自定义扩展组件
    private $components=[];
    
    public function Order(){
        // $User=new User();
        // $StateInfo=new StateInfo();
        // $StateMachine=new StateMachine();
        // $PayInfo=new PayInfo();
        // $Pay=new Pay();
        // $Message=new Message();
        // $Goods=new Goods();
        // $Logistics=new Logistics();
        // $Address=new Address();
        
    }
    
    // ===================================================================================
    // getter/setter
    
    public function setOrderId($orderId){
        $this->orderId=$orderId;
    }
    
    public function getOrderId(){
        return$this->orderId;
    }
    
    public function setGoods($Goods){
        $Goods->setOrder($this);
        $this->Goods=$Goods;
        $Goods->start();
    }
    
    public function getGoods(){
        return $this->Goods;
    }
    
    public function getPrice(){
        return $this->price;
    }
    
    public function setPrice($price){
        $this->price=$price;
    }
    
    public function valuation(){
        
        // ===================================================================================
        // 基本属性
        $total=0;
        
        // ===================================================================================
        // 商品计价
        $goods=$this->getGoods();
        $total=$goods->valuation($total);
        
        // ===================================================================================
        // 组件计价
        // 组件中有营销活动。
        foreach ($this->getComponent() as $k => $component) {
            $total=$component->valuation($total);
        }
        $this->setPrice($total);
        return $this->getPrice();
    }
    
    // ===================================================================================
    // 扩展方法
    
    /**
    * 添加一个自定义组件
    */
    public function addComponent($component){
        $this->components[]=$component;
        $component->setOrder($this);
        $component->start();
    }
    
    public function getComponent(){
        return $this->components;
    }
    
    
}