<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月23日22:56:46
* 最新修改时间：2018年8月23日22:56:46
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####一元商品控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class OneGoodsController extends CommonController{
    
    public function add(){
        $OneGoods=D('OneGoods');
        
        $goods_id=I('goods_id');
        
        $data['goods_id']=$goods_id;
        $data['is_show_alert']=0;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $result=$OneGoods->add($data);
        
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
        
        $OneGoods=D('OneGoods');
        
        $Goods=D('Goods');
        
        $page=I('page');
        $page_size=I('page_size');
        
        $goodsList=$OneGoods
        ->table('c_one_goods as t1,c_goods as t2')
        ->field('t1.*,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title,t2.sort,t2.add_time,t2.is_up')
        ->order('t2.sort desc,t2.add_time desc')
        ->where("t1.goods_id = t2.goods_id")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $res['total']=$OneGoods
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
    
    
    public function del(){
        $goods_id=I('goods_id');
        $where=[];
        $where['goods_id']=$goods_id;
        $OneGoods=D('OneGoods');
        $result=$OneGoods->where($where)->delete();
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
}