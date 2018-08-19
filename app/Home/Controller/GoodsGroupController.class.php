<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月9日18:24:063
* 最新修改时间：2018年8月9日18:24:063
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品分组控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class GoodsGroupController extends Controller{
    
    public function getList(){
        $GoodsGroupLink=D('GoodsGroupLink');
        
        $goods_group_id=I('goods_group_id');
        $where=[];
        $where['goods_group_link_id']=$goods_group_id;
        
        $GoodsGroupLink=D('GoodsGroupLink');
        $Goods=D('Goods');
        
        $special_id=I('special_id');
        $page=I('page');
        $page_size=I('page_size');
        
        $goodsList=$GoodsGroupLink
        ->cache(true,10)
        ->distinct(true)
        ->table('c_goods_group_link as t1,c_goods as t2')
        ->field('t1.*,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title,t2.sort,t2.add_time,t2.is_up')
        ->order('t2.sort desc,t2.add_time desc')
        ->where("t1.goods_group_id='$goods_group_id' AND t1.goods_id = t2.goods_id AND t1.data_status = 1 AND t2.is_up = 1 ")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $where=[];
        $where['goods_group_id']=$goods_group_id;
        $res['total']=$GoodsGroupLink
        ->where($where)
        ->count()+0;
        
        // foreach ($goodsList as $k => $v) {
        //     $where=[];
        //     $where['goods_id']=$v['goods_id'];
        //     $v['goods_head']=$GoodsImg->order('slot asc')->where($where)->getField('src');
        //     $v['price']=$Sku->where($where)->getField('price');
        //     $v['stock_num']=$Sku->where($where)->sum('stock_num');
        //     $goodsList[$k]=$v;
        // }
        
        $GoodsImg=D('GoodsImg');
        $Sku=D('Sku');
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
            // ===================================================================================
            // 计算库存总量
        }
        
        
        if($goodsList!==false){
            $res['res']=count($goodsList);
            $res['msg']=$goodsList;
        }else{
            $res['res']=-1;
            $res['msg']=$goodsList;
        }
        echo json_encode($res);
    }
    
    
}