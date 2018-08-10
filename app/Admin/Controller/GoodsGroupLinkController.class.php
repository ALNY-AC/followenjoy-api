<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月9日20:56:50
* 最新修改时间：2018年8月9日20:56:50
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品分组关联商品控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class GoodsGroupLinkController extends CommonController{
    
    public function addGoods(){
        
        $goods_id=I('goods_id');
        $goods_group_id=I('goods_group_id');
        
        $GoodsGroupLink=D('GoodsGroupLink');
        $goods_group_link_id=md5($goods_id.$goods_group_id);
        $data=[];
        $data['goods_group_link_id']=md5($goods_id.$goods_group_id);
        $data['goods_id']=$goods_id;
        $data['goods_group_id']=$goods_group_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        $result=$GoodsGroupLink->add($data,null,true);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        
        $GoodsGroupLink=D('GoodsGroupLink');
        
        $goods_id=I('goods_id');
        $goods_group_id=I('goods_group_id');
        $where=[];
        $where['goods_group_link_id']=md5($goods_id.$goods_group_id);
        $result=$GoodsGroupLink->where($where)->delete();
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
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
        ->distinct(true)
        ->table('c_goods_group_link as t1,c_goods as t2')
        ->field('t1.*,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title,t2.sort,t2.add_time,t2.is_up')
        ->order('t2.sort desc,t2.add_time desc')
        ->where("t1.goods_group_id='$goods_group_id' AND t1.goods_id = t2.goods_id")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $where=[];
        $where['goods_group_id']=$goods_group_id;
        $res['total']=$GoodsGroupLink
        ->where($where)
        ->count()+0;
        
        $GoodsImg=D('GoodsImg');
        $Sku=D('sku');
        foreach ($goodsList as $k => $v) {
            $where=[];
            $where['goods_id']=$v['goods_id'];
            $v['goods_head']=$GoodsImg->order('slot asc')->where($where)->getField('src');
            $v['price']=$Sku->where($where)->getField('price');
            $v['stock_num']=$Sku->where($where)->sum('stock_num');
            $goodsList[$k]=$v;
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