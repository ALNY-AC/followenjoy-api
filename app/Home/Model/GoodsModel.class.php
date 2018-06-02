<?php
namespace Home\Model;
use Think\Model;
class GoodsModel extends Model {
    
    public  $Goods ;
    public $SkuTree;
    public $SkuTreeV;
    public $Class;
    
    
    public function _initialize (){
        
        $this->SkuTree=D('SkuTree');
        $this->SkuTreeV=D('SkuTreeV');
        $this->Class=D('Class');
        
    }
    
    public function getList($data=[],$where=[]){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $field  =   $data['field']?$data['field']:[];
        
        $where['is_up']=1;
        
        $field=[
        'goods_id',
        'goods_title',
        'goods_banner',
        'sub_title',
        // 'freight_id',
        'is_up',
        // 'goods_class',
        'sort',
        // 'is_cross_border',
        // 'goods_content',
        // 'is_unique',
        'add_time',
        // 'edit_time'
        ];
        
        
        $list  =  $this
        ->order('sort desc,add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        //找 sku 和 tree
        
        
        for ($i=0; $i <count($list) ; $i++) {
            $goods              =     $list[$i];
            $list[$i]      =     $this->getGoodsSku($goods,$map=['img_list','sku','tree'],[]);
        }
        
        return $list;
    }
    
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        
        $field=[
        'goods_id',
        'goods_title',
        'goods_banner',
        'sub_title',
        'freight_id',
        'is_up',
        'goods_class',
        'sort',
        // 'is_cross_border',s
        // 'goods_content',
        // 'is_unique',
        'add_time',
        // 'edit_time'
        ];
        
        $where['is_up']=1;
        
        $goodsList  =  $this
        ->order('sort desc,add_time desc')
        ->where($where)
        ->field($field)
        ->select();
        
        //找 sku 和 tree
        for ($i=0; $i <count($goodsList) ; $i++) {
            $goods              =     $goodsList[$i];
            $goodsList[$i]      =     $this->getGoodsSku($goods,$map=['img_list','sku','tree'],false);
        }
        
        return $goodsList;
    }
    
    //获得一个
    public function get($goods_id,$map=['img_list','sku','tree','class','freight']){
        
        
        $where=[];
        $where['is_up']=1;
        $where['goods_id']=$goods_id;
        
        $goods=$this->where($where)->find();
        if(!$goods){
            return null;
        }
        
        $goods=$this->getGoodsSku($goods,$map,true);
        $goods=toTime([$goods])[0];
        //找是否收藏
        $model=M('collection');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['user_id']=session('user_id');
        $collection=$model->where($where)->find();
        
        $goods['is_collection']=!($collection==null);
        
        //判断是不是限时购商品
        $TimeGoods=D('TimeGoods');
        $Time=D('Time');
        $where=[];
        $where['goods_id'] = $goods_id;
        
        $timeGoods=$TimeGoods->where($where)->find();
        $time_id=$timeGoods['time_id'];
        
        $where=[];
        $where['time_id']=$time_id;
        
        $time=$Time
        ->where($where)
        ->find();
        
        if($time){
            //参加了限时购商品
            //判断过期
            $time['qj_time']=$time['end_time']-time();
            
            
            $time['end_time_text']=date('Y-m-d H:i:s',$time['end_time']);
            $time['start_time_text']=date('Y-m-d H:i:s',$time['start_time']);
            
            $goods['time']=$time;
            
        }
        
        
        return $goods;
    }
    
    
    public function search(){
        $keys=I('key');
        //先根据空格分割为数组
        
        foreach ($keys as $key => $value) {
            $keys[$key]='%'.$value.'%';
        }
        $where=[];
        $where['goods_title']=['like',$keys,'AND'];
        
        $list=  $this->getList(I(),$where);
        return $list===null ? []:$list;
        
    }
    
    public function getGoodsSku($goods,$map=['img_list','sku','tree','class','freight'],$limit=[]){
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
            'goods_id',
            'src',
            'slot',
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
            ->limit($limit['img_list'])
            ->where($where)
            ->order('price asc,stock_num desc')
            ->select();
            $goods['sku']=$skus;
        }
        
        // ===================================================================================
        // 找skutree
        if(in_array('tree',$map)){
            
            $tree= $this->SkuTree
            ->limit($limit['img_list'])
            ->where($where)
            ->order('k_s asc')
            ->select();
            for ($j=0; $j <count($tree) ; $j++) {
                //找 tree 的 v
                $sku_tree_id=$tree[$j]['sku_tree_id'];
                $where['sku_tree_id']=$sku_tree_id;
                $v=$this->SkuTreeV->where($where)->select();
                $tree[$j]['v']= $v;
            }
            $goods['tree']=$tree;
            
        }
        
        // ===================================================================================
        // 找分类信息
        
        if(in_array('class',$map)){
            $where=[];
            $where['class_id']=$goods['goods_class'];
            $class= $this->Class->where($where)->find();
            if($class['super_id']){
                // ===================================================================================
                // 有上级，找上级
                $where=[];
                $where['class_id']=$class['super_id'];
                $super=$this->Class->where($where)->find();
                $class['super']=$super;
            }
            $goods['class']=$class;
        }
        
        
        $label=[];
        // $label['type']=1;
        // $label['label']="特卖";
        $goods['goodsLabel'][]=$label;
        
        // $label=[];
        // $label['type']=2;
        // $label['label']="预售";
        // $goods['goodsLabel'][]=$label;
        
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