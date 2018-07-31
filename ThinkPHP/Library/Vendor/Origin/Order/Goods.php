<?php
class Goods {
    
    // ===================================================================================
    // 基本属性
    
    private $values=[];//数据体
    private $order;
    
    public function Goods(){
        $this->values=$this->getGoodsTemp();
    }
    
    // ===================================================================================
    // 基本函数
    
    public function setImg($img=''){
        $this->values['img']=$img;
    }
    public function getImg(){
        return $this->values['img'];
    }
    
    public function setTitle($title=''){
        $this->values['title']=$title;
    }
    public function getTitle(){
        return $this->values['title'];
    }
    
    public function setSkuInfo($skuInfo=''){
        $this->values['skuInfo']=$skuInfo;
    }
    public function getSkuInfo(){
        return $this->values['skuInfo'];
    }
    
    public function setNum($num=''){
        $this->values['num']=$num+0;
    }
    public function getNum(){
        return $this->values['num'];
    }
    
    public function setPrice($price=''){
        $this->values['price']=$price+0.00;
    }
    public function getPrice(){
        return $this->values['price'];
    }
    
    public function getValues(){
        return $this->values;
    }
    
    public function setOrder($order){
        $this->order=$order;
    }
    
    public function getOrder(){
        return  $this->order;
    }
    
    public function setSkuId($skuId=''){
        $this->values['skuId']=$skuId;
    }
    
    public function getSkuId(){
        return $this->values['skuId'];
    }
    
    public function setGoodsId($goodsId){
        $this->values['goodsId']=$goodsId;
    }
    
    
    public function getGoodsId(){
        return $this->values['goodsId'];
    }
    
    
    // ===================================================================================
    // 扩展函数
    public function getGoodsTemp(){
        $goods=[];
        $goods['img']='';//图片
        $goods['title']='';//标题
        $goods['skuInfo']='';//sku 的描述信息
        $goods['num']=1;//数量
        $goods['price']=0.01;//单价
        return $goods;
    }
    
    
    
    
}