<?php
/**
 * Created by PhpStorm.
 * User: xukaibing
 * Date: 2018/8/9
 * Time: 17:47
 */
namespace Home\Controller;

use Think\Controller;

class RecommendController extends Controller
{
    public function getList(){
      $Recommend=D('Recommend');
      $goodsList=$Recommend
          ->table('c_recommend as t1,c_goods as t2')
          ->field('t1.*,t2.goods_id,t2.goods_title,t2.sub_title,t2.is_up,t2.sort')
          ->where('t1.goods_id=t2.goods_id AND t2.is_up = 1')
          ->order('t2.sort desc')
          ->select()
          ;

     $Goods=D('Goods');
     foreach ($goodsList as $k=>$v){
        $v=$Goods->getGoodsSku($v,$map=['img_list','sku','tree'],false);
        $goodsList[$k]=$v;
     }

            dump($goodsList);
    }
}