<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月23日22:55:30
* 最新修改时间：2018年8月23日22:55:30
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####一元商品模型#####
* @author 代码狮
*
*/
namespace Common\Model;
use Think\Model;
class OneGoodsModel extends Model {
    
    public function isGoods($goods_id){
        $where=[];
        $where['goods_id']=$goods_id;
        return $this->where($where)->find();
    }
    
}