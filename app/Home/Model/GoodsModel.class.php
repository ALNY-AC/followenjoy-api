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
        $where['is_unique']=0;
        
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
        
        for ($i=0; $i <count($list) ; $i++) {
            
            $goods=$list[$i];
            $goods=$this->getGoodsSku($goods,$map=['img_list','sku','tree'],false);
            $goods=$this->getTime($goods);
            $list[$i]=$goods;
            
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
        // 'is_cross_border',
        // 'goods_content',
        // 'is_unique',
        'add_time',
        // 'edit_time'
        ];
        
        $where['is_up']=1;
        $where['is_unique']=0;
        
        $list  =  $this
        ->order('sort desc,add_time desc')
        ->where($where)
        ->field($field)
        ->select();
        
        for ($i=0; $i <count($list) ; $i++) {
            
            $goods=$list[$i];
            $goods=$this->getGoodsSku($goods,$map=['img_list','sku','tree'],false);
            $goods=$this->getTime($goods);
            $list[$i]=$goods;
            
        }
        
        return $list;
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
        
        //配置限时购商品
        $goods= $this->getTime($goods);
        
        
        
        // c_record 添加浏览记录
        $this->createRecord($goods_id);
        
        return $goods;
    }
    
    public function createRecord($goods_id){
        
        $Record=D('Record');
        $user_id=session('user_id');
        
        $UserSuper=D('UserSuper');
        
        $where=[];
        $where['user_id']=$user_id;
        $super=$UserSuper->where($where)->find();
        
        $User=D('User');
        $where=[];
        $where['user_id']=$super['super_id'];
        $user=$User->where($where)->find();
        $data=[];
        if($super){
            $data['shop_id']=$user['shop_id'];
        }else{
            $data['shop_id']='';
        }
        
        $data['goods_id']=$goods_id;
        $data['user_id']=$user_id;
        return $Record->create($data);
    }
    
    // 取得限时购数据
    public function getTime($goods){
        
        $TimeGoods=D('TimeGoods');
        
        $goods_id=$goods['goods_id'];
        
        $where=[];
        $where['goods_id'] = $goods_id;
        
        $time=$TimeGoods->where($where)->find();
        
        if(!$time){
            return $goods;
        }
        
        // $toTime=time();
        
        // $start_time=$time['start_time'];
        // $end_time=$time['end_time'];
        
        // if($toTime>$start_time && $toTime < $end_time){
        // 限时购商品，正在进行时
        
        foreach ($goods['sku'] as $k => $v) {
            
            $v['original_price']=$v['price'];
            $v['price'] =   $v['activity_price'];
            $v['earn_price'] =   $v['activity_earn_price'];
            
            $goods['sku'][$k]=$v;
            
        }
        
        // }
        
        
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
        // http://q.followenjoy.cn/#/TempPages?temp_pages_id=1632c332fd0ff5633a7072ad978bd739
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