<?php
class Goods extends OrderComponent {
    
    // ===================================================================================
    // 关联属性
    private $Order;//关联的订单
    
    // ===================================================================================
    // 自有属性
    private $num=1;//选择的数量
    private $price=0;//单价
    private $img='';//一张图描述
    private $title='';//商品的名字
    private $class;//商品所属分区的id
    
    
    public function start(){
        // ec(__METHOD__);
    }
    
    public function setNum($num){
        $this->num=$num;
    }
    
    public function getNum(){
        return $this->num;
    }
    
    public function setPrice($price){
        $this->price=$price;
    }
    
    public function getPrice(){
        return $this->price;
    }
    
    public function setImg($img){
        $this->img=$img;
    }
    
    public function getImg(){
        return $this->img;
    }
    
    public function setOrder($Order){
        $this->Order=$Order;
    }
    
    public function getOrder(){
        return $this->Order;
    }
    
    public function setClass($class){
        $this->class=$class;
    }
    public function getClass(){
        return $this->class;
    }
    
    public function setTitle($title){
        $this->title=$title;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    // ===================================================================================
    // 逻辑
    
    /**FS
    * 计价钩子函数
    * 当计价发生时，调用该函数
    * @param Float total 订单总价，没有经过处理的订单总价，即商品单价*商品数量
    * @return Float 返回修正后的价格。
    */
    public function valuation($total){
        
        $total+=$this->getNum()*$this->getPrice();
        
        // ===================================================================================
        // 组件计价
        // 比如时间轴这种需要从商品价格上修改的情况
        foreach ($this->getComponents() as $k => $component) {
            $total=$component->valuation($total);
        }
        
        return $total;
        
    }
    
    
    
    
}