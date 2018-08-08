<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
    
    public function _initialize (){}
    
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
        
        // dump($field);
        if(!$field){
            $field=[
            'goods_id',
            'goods_title',
            'goods_banner',
            'sub_title',
            // 'freight_id',
            'is_up',
            'goods_class',
            'sort',
            // 'is_cross_border',
            // 'goods_content',
            // 'is_unique',
            // 'add_time',
            // 'edit_time'
            ];
        }
        
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
    
    public function getAll($ids,$data){
        $field  =   $data['field']?$data['field']:[];
        
        if(!$field){
            $field=[
            'goods_id',
            'goods_title',
            'goods_banner',
            'sub_title',
            // 'freight_id',
            'is_up',
            'goods_class',
            'sort',
            // 'is_cross_border',
            // 'goods_content',
            // 'is_unique',
            // 'add_time',
            // 'edit_time'
            ];
        }
        
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
        $where['goods_id']=['in',getIds($goods_id)];
        $result=$this->where($where)->delete();
        
        $Models=[];
        
        $Models['NavGoods']=$NavGoods=D('NavGoods');
        $Models['GoodsImg']=$GoodsImg=D('GoodsImg');
        $Models['GoodsLabel']=$GoodsLabel=D('GoodsLabel');
        $Models['Sku']=$Sku=D('Sku');
        $Models['SkuTree']=$SkuTree=D('SkuTree');
        $Models['SkuTreeV']=$SkuTreeV=D('SkuTreeV');
        $Models['SpecialGoods']=$SpecialGoods=D('SpecialGoods');
        $Models['TimeGoods']=$TimeGoods=D('TimeGoods');
        
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
        $goods['goods_head']='';
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
            ->field(
            [
            // 'img_id',
            // 'goods_id',
            'src',
            // 'slot',
            // 'add_time',
            // 'edit_time',
            ]
            )
            ->select();
            $goods['img_list']=$img_list?$img_list:[];
            $goods['goods_head']=count($goods['img_list'])>0?$goods['img_list'][0]['src']:'';
        }
        
        // ===================================================================================
        // 找sku
        if(in_array('sku',$map)){
            $Sku=D('sku');
            $skus= $Sku
            ->limit($limit['sku'])
            ->where($where)
            ->field(
            [
            'goods_id',
            'sku_id',
            'img_url',
            'id',
            'price',
            's1',
            's2',
            's3',
            // 'tax',
            'stock_num',
            'purchase_price',
            'earn_price',
            // 'supplier_id',
            // 'shop_code',
            // 'amount',
            'activity_price',
            'activity_earn_price',
            'sales_volume',
            ]
            )
            ->order('price asc,stock_num desc')
            ->select();
            $goods['sku']=$skus?$skus:[];
            $goods['price']=$Sku->where($where)->getField('price');
            $goods['stock_num']=$Sku->where($where)->sum('stock_num');
            
        }
        
        // ===================================================================================
        // 找skutree
        if(in_array('tree',$map)){
            
            $SkuTree=D('sku_tree');
            $SkuTreeV=D('sku_tree_v');
            $tree= $SkuTree
            ->limit($limit['tree'])
            ->where($where)
            ->order('k_s asc')
            ->field(
            [
            'sku_tree_id',
            // 'goods_id',
            'k',
            'k_s',
            // 'add_time',
            // 'edit_time',
            ]
            )
            ->select();
            
            foreach ($tree as $k => $v) {
                $sku_tree_id=$v['sku_tree_id'];
                $where['sku_tree_id']=$sku_tree_id;
                $s_v=$SkuTreeV
                ->field(
                [
                'v_id',
                'goods_id',
                'sku_tree_id',
                'id',
                'name',
                'img_url',
                'add_time',
                'edit_time',
                ]
                )
                ->where($where)
                ->select();
                $v['v']= $s_v;
                $tree[$k]= $v;
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
        
        
        // $label=[];
        // $label['type']=1;
        // $label['label']="特卖";
        // $goods['goodsLabel'][]=$label;
        
        // $label=[];
        // $label['type']=2;
        // $label['label']="预售";
        // $goods['goodsLabel'][]=$label;
        $goods['goodsLabel']=[];
        
        // ===================================================================================
        // 取得商品的物流模板
        if(in_array('freight',$map)){
            $Freight=D('Freight');
            $freight=$Freight->get($goods['freight_id']);
            $goods['freight']=$freight;
        }
        
        
        return $goods;
    }
    
    
    public function printData(){
        
        $goods=$this
        ->field('goods_id,freight_id,goods_class,goods_title,is_up,is_cross_border')
        ->order('goods_id')
        ->select();
        $Sku=D('Sku');
        
        $sku=$Sku->select();
        
        $header=[
        '供货商名',
        '商品分类',
        '商品ID',
        'SKU编码',
        '商品标题',
        '商品规格1',
        '商品规格2',
        '商品规格3',
        '商品税率',
        '采购成本价',
        '正常售价',
        '佣金金额/比例',
        '活动售价',
        '活动佣金金额/比例',
        '库存',
        '上架状态',
        '运费模板',
        '是否是跨境商品',
        ];
        
        $list=[];
        foreach ($sku as $k => $v) {
            
            $item=[];
            
            $goodsInfo=$this->getGoodsInfo($v['goods_id'],$goods);
            $supplier_info=$this->getSupplier($v['supplier_id']);
            $freight_info=$this->getFreight($goodsInfo['freight_id']);
            $class_title=$this->getClassName($goodsInfo['goods_class']);
            // ===================================================================================
            // 重新排序
            $item['供货商名']=$supplier_info['supplier_name'];
            $item['商品分类']= $class_title;
            $item['商品ID']=$v['goods_id'];
            $item['SKU编码']=$v['shop_code'];
            $item['商品标题']=$goodsInfo['goods_title'];
            $item['商品规格1']=$v['s1'];
            $item['商品规格2']=$v['s2'];
            $item['商品规格3']=$v['s3'];
            $item['商品税率']=$v['tax'];
            $item['采购成本价']=$v['purchase_price'];
            $item['正常售价']=$v['price'];
            $item['佣金金额/比例']=$v['earn_price'];
            $item['活动售价']=$v['activity_price'];
            $item['活动佣金金额/比例']=$v['activity_earn_price'];
            $item['库存']=$v['stock_num'];
            $item['上架状态']=$goodsInfo['is_up']?'上架中':'未上架';
            $item['运费模板']=$freight_info['freight_name'];
            $item['是否是跨境商品']=$goodsInfo['is_cross_border']?'是':'否';
            
            if($goodsInfo){
                $list[]=$item;
            }
            
        }
        
        
        array_unshift($list,$header);
        $fileName="商品数据";
        create_xls($list,$fileName);
        // dump($list);
    }
    
    private function getGoodsInfo($goods_id,$list){
        
        foreach ($list as $k => $v) {
            if($v['goods_id'] == $goods_id){
                return $v;
            }
            
        }
        return null;
        
    }
    
    private function getSupplier($supplier_id){
        $Supplier=D('Supplier');
        return $Supplier
        ->where(['supplier_id'=>$supplier_id])
        ->field('supplier_name')
        ->find();
        
    }
    
    private function getFreight($freight_id){
        $Freight=D('Freight');
        return $Freight
        ->where(['supplier_id'=>$freight_id])
        ->field('freight_name')
        ->find();
        
    }
    
    private function getClassName($class_id){
        $Class=D('Class');
        $class_title='';
        $class=$Class
        ->where(['class_id'=>$class_id])
        ->field('class_title,super_id')
        ->find();
        $class_title=$class['class_title'];
        if($class['super_id']){
            
            $super=$Class
            ->where(['class_id'=>$class['super_id']])
            ->field('class_title,super_id')
            ->find();
            
            $class_title.='/'.$super['class_title'];
        }
        
        return $class_title;
        
    }
}