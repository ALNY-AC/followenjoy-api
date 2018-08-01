<?php
namespace Home\Model;
use Think\Model;
class GoodsModel extends Model {
    
    public $Goods ;
    public $SkuTree;
    public $SkuTreeV;
    public $Class;
    public $TimeGoods;
    
    
    public function _initialize (){
        
        $this->SkuTree=D('SkuTree');
        $this->SkuTreeV=D('SkuTreeV');
        $this->Class=D('Class');
        $this->TimeGoods=D('TimeGoods');
        
    }
    
    public function testGestList(){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $field  =   $data['field']?$data['field']:[];
        
        $where['is_up']=1;
        $where['is_unique']=0;
        
        // dump($field);
        if(!$field){
            $field=[
            'goods_id',
            'goods_title',
            'goods_banner',
            'sub_title',
            // 'freight_id',
            // 'is_up',
            // 'goods_class',
            // 'sort',
            // 'is_cross_border',
            // 'goods_content',
            // 'is_unique',
            // 'add_time',
            // 'edit_time'
            ];
        }
        
        $list  =  $this
        ->cache(true,5)
        ->order('sort desc,add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        foreach ($list as $k => $v) {
            $v=$this->getGoodsSku($v,$map=['img_list','sku','tree'],false);
            $v=$this->getTime($v);
            $list[$k]=$v;
        }
        
        return $list;
    }
    
    public function getList($data=[],$where=[]){
        
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $field  =   $data['field']?$data['field']:[];
        
        $where['is_up']=1;
        $where['is_unique']=0;
        
        // dump($field);
        if(!$field){
            $field=[
            'goods_id',
            'goods_title',
            'goods_banner',
            'sub_title',
            // 'freight_id',
            // 'is_up',
            // 'goods_class',
            // 'sort',
            // 'is_cross_border',
            // 'goods_content',
            // 'is_unique',
            // 'add_time',
            // 'edit_time'
            ];
        }
        
        $list  =  $this
        ->order('sort desc,add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        foreach ($list as $k => $v) {
            $v=$this->getGoodsSku($v,$map=['img_list','sku','tree'],false);
            $v=$this->getTime($v);
            $list[$k]=$v;
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
    public function get($goods_id,$map=['img_list','sku','tree','class','freight'],$field,$limit=true){
        
        $where=[];
        $where['is_up']=1;
        $where['goods_id']=$goods_id;
        
        $goods=$this
        ->field($field)
        ->where($where)
        ->find();
        
        if(!$goods){
            return null;
        }
        
        $goods=$this->getGoodsSku($goods,$map,$limit);
        $goods=toTime([$goods])[0];
        //找是否收藏
        $Collection=D('Collection');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['user_id']=I('user_id');
        $collection=$Collection->where($where)->find();
        $goods['is_collection']=!($collection==null);
        
        
        //配置限时购商品
        $goods= $this->getTime($goods);
        
        
        // c_record 添加浏览记录
        $this->createRecord($goods_id);
        
        return $goods;
    }
    
    
    
    public function createRecord($goods_id){
        
        if(!session('user_id')){
            return;
        }
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
        
        $goods_id=$goods['goods_id'];
        
        // 先取今天的
        // 没有的话再取昨天的
        // 在没有的话再取明天的
        
        $今天0点=mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $今天23点=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        // dump(date('Y-m-d H:i:s',$今天0点));
        // dump(date('Y-m-d H:i:s',$今天23点));
        
        
        
        $昨天0点=mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
        $昨天23点=mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
        // dump(date('Y-m-d H:i:s',$昨天0点));
        // dump(date('Y-m-d H:i:s',$昨天23点));
        
        
        
        $明天0点=mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
        $明天23点=mktime(23, 59, 59, date('m'), date('d')+1, date('Y'));
        // dump(date('Y-m-d H:i:s',$明天0点));
        // dump(date('Y-m-d H:i:s',$明天23点));
        
        
        $where=[];
        // 限制时间范围
        $where['start_time']=[];
        $where['start_time']=[['EGT',$今天0点],['ELT',$今天23点]];
        $where['goods_id'] = $goods_id;
        $time=$this->TimeGoods->where($where)->find();
        
        if(!$time){
            // 不在今天
            
            // 那就查查昨天的
            
            $where=[];
            // 限制时间范围
            $where['start_time']=[];
            $where['start_time']=[['EGT',$昨天0点],['ELT',$昨天23点]];
            $where['goods_id'] = $goods_id;
            $time=$this->TimeGoods->where($where)->find();
            
            if(!$time){
                // 昨天不存在
                // 那就查查明天的
                
                $where=[];
                // 限制时间范围
                $where['start_time']=[];
                $where['start_time']=[['EGT',$明天0点],['ELT',$明天23点]];
                $where['goods_id'] = $goods_id;
                $time=$this->TimeGoods->where($where)->find();
                
                if(!$time){
                    // 商品不在明天的时间轴上
                    // 商品不在三天时间轴上
                    $goods['is_time']=false;
                    return $goods;
                    
                }else{
                    // 商品在明天的时间抽上
                    $goods['is_time']=true;
                    $goods['test']='明天';
                }
                
            }else{
                // 商品在昨天的时间抽上
                $goods['is_time']=true;
                $goods['test']='昨天';
            }
            
        }else{
            // 在 今天
            $goods['is_time']=true;
            $goods['test']='今天';
            
        }
        
        
        $toTime=time();
        $start_time=$time['start_time'];
        $end_time=$time['end_time'];
        
        if( $goods['is_time']){
            foreach ($goods['sku'] as $k => $v) {
                
                $v['original_price']=$v['price'];
                $v['price'] =   $v['activity_price'];
                $v['earn_price'] =   $v['activity_earn_price'];
                $goods['sku'][$k]=$v;
                
            }
            
            $label=[];
            $label['type']=1;
            $label['label']="特卖";
            $goods['goodsLabel'][]=$label;
        }
        
        // if($toTime>$start_time && $toTime < $end_time){
        //     // 范围内
        // }
        
        
        // 检测是否还未到时间
        if($toTime<$start_time){
            // 时间还未到
            $goods['not_time']=true;
            
        }else{
            // 已经开始,或者结束，此参数不可以判断活动是否结束。
            $goods['not_time']=false;
        }
        $goods['activity_time']=$time['start_time'];
        
        
        
        // dump(date('Y-m-d h:i:s',$start_time));
        // dump(date('Y-m-d h:i:s',$end_time));
        // dump($goods);
        // die;
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
            $goods['sku']=$skus;
        }
        // http://q.followenjoy.cn/#/TempPages?temp_pages_id=1632c332fd0ff5633a7072ad978bd739
        // ===================================================================================
        // 找skutree
        if(in_array('tree',$map)){
            
            $tree= $this->SkuTree
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
                $s_v=$this
                ->SkuTreeV
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
        
        
        // $label=[];
        // $label['type']=1;
        // $label['label']="特卖";
        $goods['goodsLabel']=[];
        
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