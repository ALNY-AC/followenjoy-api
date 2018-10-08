<?php
namespace Common\Model;
use Think\Model;
class KillModel extends Model {
    // ===================================================================================
    // 判断是否是秒杀商品
    public function is($goods_id){
        $TimeGoods=D('TimeGoods');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['type']='kill';
        return $TimeGoods->where($where)->find();
    }
    
}