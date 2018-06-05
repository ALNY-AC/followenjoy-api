<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
    
    public  $Goods ;
    
    public function _initialize (){
        $this->Goods=M('goods');
    }
    
    public function creat($add){
        
        $data=$add;
        unset($add['sku']);
        unset($add['tree']);
        unset($add['img_list']);
        
        $add['add_time']=time();
        $add['edit_time']=time();
        
        $goods_id=$this->add($add);
        
        $this->addGoodsImg($goods_id,$data);
        $this->addGoodsSku($goods_id,$data);
        $this->addGoodsLabel($goods_id,$data);
        
        return true;
        
    }
    
    public function addGoodsLabel($goods_id,$data){
        $GoodsLabel=D('GoodsLabel');
        $where=[];
        $where['goods_id']=$goods_id;
        $GoodsLabel->where($where)->delete();
        
        $adds=$data['label'];
        
        foreach ($adds as $k => $v) {
            
            $v['goods_label_id']=getMd5('goodsLabel');
            $v['goods_id']=$goods_id;
            $v['add_time']=time();
            $v['edit_time']=time();
            
            $adds[$k]=$v;
        }
        
        return $GoodsLabel->addAll($adds);
        
    }
    
    public function saveData($goods_id,$save){
        $where=[];
        $where['goods_id']=$goods_id;
        return $this->where($where)->save($save);
    }
    
    function addGoodsImg($goods_id,$data){
        $GoodsImg=M('goods_img');//商品图片
        $where=[];
        $where['goods_id']=$goods_id;
        $GoodsImg->where($where)->delete();
        
        //重新添加商品图片
        $imgs=[];
        $imgList=$data['img_list'];
        
        for ($i=0; $i < count($imgList); $i++) {
            $url=$imgList[$i];
            $item=[];
            $item['img_id']=getMd5($goods_id.'goodsImg');
            $item['goods_id']=$goods_id;
            $item['src']=$url;
            $item['add_time']=time();
            $item['edit_time']=time();
            $item['slot']=$i;
            $imgs[]=$item;
        }
        return $GoodsImg->addAll($imgs);
    }
    
    
    //添加sku
    function addGoodsSku($goods_id,$data){
        $Sku=M('sku');//sku
        $SkuTree=M('sku_tree');//sku树
        $SkuTreeV=M('sku_tree_v');//sky树的v
        
        $where=[];
        $where['goods_id']=$goods_id;
        
        $Sku->where($where)->delete();
        $SkuTree->where($where)->delete();
        $SkuTreeV->where($where)->delete();
        
        //重新添加sku
        $skus=$data['sku'];
        for ($i=0; $i < count($skus); $i++) {
            $item=$skus[$i];
            $item['goods_id']=$goods_id;
            $sku_id=$goods_id.$item['price'].$item['earn_price'].$item['purchase_price'].$item['supplier_id'].$item['tax'].$item['shop_code'].$item['s1'].$item['s2'].$item['s3'];
            $sku_id=md5($sku_id);
            $item['sku_id']=$sku_id;
            $item['add_time']=time();
            $item['edit_time']=time();
            $skus[$i]=$item;
        }
        
        //重新添加 sku tree
        $trees=$data['tree'];
        $treeV=[];
        for ($i=0; $i < count($trees); $i++) {
            
            $tree=$trees[$i];
            $sku_tree_id=md5($goods_id.$tree['k']);
            $tree['sku_tree_id']=$sku_tree_id;
            $tree['goods_id']=$goods_id;
            $tree['add_time']=time();
            $tree['edit_time']=time();
            
            $trees[$i]=$tree;
            //添加 tree 的 v
            for ($j=0; $j <count($tree['v']) ; $j++) {
                $v=$tree['v'][$j];
                $v['v_id']=md5($goods_id.$sku_tree_id.$v['id']);
                $v['goods_id']=$goods_id;
                $v['sku_tree_id']=$sku_tree_id;
                $v['img_url']='';
                $v['add_time']=time();
                $v['edit_time']=time();
                $treeV[]=$v;
            }
            unset($tree['v']);
        }
        
        $Sku->addAll($skus);
        $SkuTree->addAll($trees);
        $SkuTreeV->addAll($treeV);
        return true;
    }
    
    
    public function getList($data){
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        
        $goodsList  =  $this
        ->order('add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        //找 sku 和 tree
        
        for ($i=0; $i <count($goodsList) ; $i++) {
            $goods              =     $goodsList[$i];
            $goodsList[$i]      =     $this->getGoodsSku($goods);
        }
        
        
        return $goodsList;
        
    }
    
    public function getAll($ids){
        $field  =   $data['field']?$data['field']:[];
        
        if($ids){
            $where['goods_id']=['in',$ids];
        }else{
            $where=[];
        }
        
        $goodsList  =  $this
        ->order('add_time desc')
        ->field($field)
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        //找 sku 和 tree
        for ($i=0; $i <count($goodsList) ; $i++) {
            $goods              =     $goodsList[$i];
            $goodsList[$i]      =     $this->getGoodsSku($goods);
        }
        
        $goodsList=toTime($goodsList);
        return $goodsList;
        
    }
    
    //获得一个
    public function get($goods_id,$map=['img_list','sku','tree','class','freight']){
        
        $data=I();
        $field  =   $data['field']?$data['field']:[];
        
        $where=[];
        $where['goods_id']=$goods_id;
        
        $goods=$this
        ->field($field)
        ->where($where)
        ->find();
        if(!$goods){
            return null;
        }
        
        $goods=$this->getGoodsSku($goods,$map,true);
        $goods=toTime([$goods])[0];
        return $goods;
    }
    
    
    //删除事务
    public function del($goods_id){
        //删除关联的东西
        $is=true;
        
        $where=[];
        $where['goods_id']=['in',$goods_id];
        $result=$this->where($where)->delete();
        
        $Models=[];
        
        $Models['NavGoods']=$NavGoods=D('NavGoods');
        $Models['GoodsImg']=$GoodsImg=D('GoodsImg');
        $Models['Sku']=$Sku=D('Sku');
        $Models['SkuTree']=$SkuTree=D('SkuTree');
        $Models['SkuTreeV']=$SkuTreeV=D('SkuTreeV');
        $Models['SpecialGoods']=$SpecialGoods=D('SpecialGoods');
        $Models['Time']=$Time=D('Time');
        //删除阵列
        foreach ($Models as $key => $Model) {
            $is=$Model->where($where)->delete()!==false;
            if($is==false){
                return $is;
            }
        }
        return $is;
    }
    
    public function getGoodsSku($goods,$map=['img_list','sku','tree','class','freight'],$limit){
        
        if(!$limit){
            $limit=[];
            $limit['img_list']=1;
        }
        
        $goods_id=$goods['goods_id'];
        
        $where=[];
        $where['goods_id']=$goods_id;
        
        // ===================================================================================
        // 找图片
        if(in_array('img_list',$map)){
            $GoodsImg=D('goods_img');
            $img_list=$GoodsImg
            ->limit($limit['img_list'])
            ->where($where)
            ->order('slot asc')
            ->select();
            $goods['img_list']=$img_list?$img_list:[];
            $goods['goods_head']=count($goods['img_list'])>0?$goods['img_list'][0]['src']:'';
        }
        
        // ===================================================================================
        // 找sku
        if(in_array('sku',$map)){
            $Sku=D('sku');
            $skus= $Sku
            ->where($where)
            ->order('price asc,stock_num desc')
            ->select();
            $goods['sku']=$skus?$skus:[];
        }
        
        // ===================================================================================
        // 找skutree
        if(in_array('tree',$map)){
            
            $SkuTree=D('sku_tree');
            $SkuTreeV=D('sku_tree_v');
            $tree= $SkuTree
            ->where($where)
            ->order('k_s asc')
            ->select();
            for ($j=0; $j <count($tree) ; $j++) {
                //找 tree 的 v
                $sku_tree_id=$tree[$j]['sku_tree_id'];
                $where['sku_tree_id']=$sku_tree_id;
                $v= $SkuTreeV->where($where)->select();
                $tree[$j]['v']= $v;
            }
            $goods['tree']=$tree;
            
        }
        
        // ===================================================================================
        // 找分类信息
        
        if(in_array('class',$map)){
            $Class=D('Class');
            $where=[];
            $where['class_id']=$goods['goods_class'];
            $class=$Class
            ->where($where)
            ->find();
            if($class['super_id']){
                // ===================================================================================
                // 有上级，找上级
                $where=[];
                $where['class_id']=$class['super_id'];
                $super=$Class->where($where)->find();
                $class['super']=$super;
            }
            $goods['class']=$class;
        }
        
        
        $label=[];
        $label['type']=1;
        $label['label']="特卖";
        $goods['goodsLabel'][]=$label;
        
        $label=[];
        $label['type']=2;
        $label['label']="预售";
        $goods['goodsLabel'][]=$label;
        
        // ===================================================================================
        // 取得商品的物流模板
        if(in_array('freight',$map)){
            $Freight=D('Freight');
            $freight=$Freight->get($goods['freight_id']);
            $goods['freight']=$freight;
        }
        
        
        return $goods;
    }
    
    
}