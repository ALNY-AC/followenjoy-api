<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日10:25:37
* 最新修改时间：2018年3月2日10:25:37
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户收藏控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CollectionController extends CommonController{
    
    public function change(){
        $Collection=D('Collection');
        $result=$Collection->change(I());
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getList(){
        
        
        $Collection=D('Collection');
        
        $page=I('page');
        $page_size=I('page_size');
        
        $where=[];
        $where['user_id']=session('user_id');
        
        $Goods=D('Goods');
        
        $Model=M();
        $goodsList=$Model
        ->table('c_collection as t1,c_goods as t2')
        ->field('t1.goods_id,t2.add_time,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title')
        ->where($where)
        ->where('t1.goods_id = t2.goods_id')
        ->order('t1.add_time desc')
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        
        
        // ===================================================================================
        // 创建，模型
        $Sku=D('Sku');//sku的模型
        $GoodsImg=D('GoodsImg');//商品图片的模型
        
        // ===================================================================================
        // 找sku，但是只取一个
        foreach ($goodsList as $k => $v) {
            // ===================================================================================
            // sku
            $goods_id=$v['goods_id'];
            $sku=$Sku->getOne($goods_id);
            $v['sku']=$sku;
            
            // ===================================================================================
            // 商品的图片
            $img_list=$GoodsImg->getOne($goods_id);
            $v['img_list']=$img_list;
            $v['goods_head']=$img_list[0];
            $goodsList[$k]=$v;
        }
        
        // ===================================================================================
        // 取商品的图片
        
        if($goodsList!=false){
            $res['res']=count($goodsList);
            $res['msg']=$goodsList;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
        
    }
    
}