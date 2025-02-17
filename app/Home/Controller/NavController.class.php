<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月21日11:11:46
* 最新修改时间：2018年5月21日11:11:46
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####导航控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class NavController extends Controller{
    
    public function getList(){
        $Nav=D('Nav');
        $result=$Nav->getList(I());
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        $Nav=D('Nav');
        $result=$Nav->get(I('nav_id'));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getGoods(){
        $Nav=D('Nav');
        $result=$Nav->getGoods(I('nav_id'),I());
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function getGoodsPlus(){
        
        $nav_id=I('nav_id');
        
        $page=I('page');
        $page_size=I('page_size');
        
        $NavGoods=D('NavGoods');
        $where=[];
        $where['nav_id']=$nav_id;
        
        $ids=$NavGoods
        ->distinct(true)
        // ->cache(true,60)
        ->table('c_nav_goods as t1,c_goods as t2')
        ->field('t1.goods_id,t1.nav_id,t2.goods_id,t2.is_up')
        ->where($where)
        ->where("t2.is_up = 1 AND t1.goods_id=t2.goods_id")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        
        $goodsIds=[];
        foreach ($ids as $k => $v) {
            $goodsIds[]=$v['goods_id'];
        }
        
        // ec($NavGoods->_sql());
        // dump($goodsIds);
        // die;
        $where=[];
        $where['goods_id']=['in',getIds($goodsIds)];
        // $where['is_up']=1;
        
        $Goods=D('Goods');
        
        $goodsList=$Goods
        ->where($where)
        // ->cache(true,60)
        ->field(
        [
        'goods_id',
        'goods_title',
        'goods_banner',
        'sub_title',
        ]
        )
        ->order('sort desc,add_time desc')
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
            
            
            // ===================================================================================
            // 计算库存总量
        }
        
        
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