<?php
class Pay {
    // ===================================================================================
    // 基本属性
    private $pay_id;
    private $price;
    
    // ===================================================================================
    // 扩展属性
    private $total;
    private $components=[];//组件的扩展组件
    
    
    private $orderList=[];
    
    public function Pay(){
        // ec(__METHOD__);
    }
    
    // ===================================================================================
    // getter/setter
    /**
    * 设置支付单id
    */
    public function setPayId($pay_id){
        $this->pay_id=$pay_id;
    }
    /**
    * 取得支付单id
    */
    public function getPayId(){
        return $this->pay_id;
    }
    
    /**
    * 设置支付总额
    */
    public function setPrice($price){
        return $this->price=$price;
    }
    
    /**
    * 获得支付总额
    */
    public function getPrice(){
        return $this->price;
    }
    
    /**
    * 取得订单列表
    */
    public function getOrderList(){
        return $this->orderList;
    }
    
    /**
    * 设置订单列表
    */
    public function setOrderList($orderList){
        $this->orderList=$orderList;
    }
    
    /**
    *取得总价
    */
    public function getTotal(){
        return $this->total;
    }
    
    /**
    * 设置总价
    */
    public function setTotal($total){
        $this->total=$total;
    }
    
    // ===================================================================================
    // 扩展方法
    
    /**
    * 添加一个自定义组件
    */
    public function addComponent($component){
        $this->components[]=$component;
        $component->setSuper($this);
        $component->start();
    }
    
    public function getComponents(){
        return $this->components;
    }
    
    // ===================================================================================
    // 逻辑层
    
    /**
    * 向集合中添加一个订单对象
    * @return 返回添加后数组的长度
    */
    public function addOrder($order){
        $this->orderList[]=$order;
        return count($this->getOrderList());
    }
    
    /**
    * 从集合中查询一个订单
    * @return 找到返回对象，找不到返回null
    */
    public function getOrder($orderId){
        foreach ($this->getOrderList() as $index => $order) {
            if($order->getOrderId()==$orderId){
                return $order;
            }
        }
        return null;
    }
    
    /**
    * 计算价格
    * 根据orderlist中的单价来计算总价
    */
    public function valuation(){
        $total=0;
        
        foreach ($this->getOrderList() as $index=>$order) {
            $order->valuation();//计价
            $price=$order->getPrice();//取得价格
            $total+=$price;//计算总价
        }
        
        // ===================================================================================
        // 组件计价
        foreach ($this->components as $index=>$comp) {
            $total=$comp->valuation($total);//计价
        }
        
        $this->setTotal($total);//设置总价
        return $this->getTotal();//返回
    }
    
    // ===================================================================================
    // 数据层
    public function getDB(){}
    
    // ===================================================================================
    // 业务层
    
    
    
    
    
    
    
}