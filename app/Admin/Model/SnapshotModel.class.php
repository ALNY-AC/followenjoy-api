<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月15日09:49:41
* 最新修改时间：2018年4月15日09:49:41
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品快照模型#####
* @author 代码狮
*
*/
namespace Admin\Model;
use Think\Model;
class SnapshotModel extends Model {
    
    public function _initialize (){}
    
    //取得一个
    public function get($snapshot_id){
        //==================================================================
        //创建模型
        $Goods=D('Goods');
        
        //==================================================================
        
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $snapshot=$this->where($where)->find();
        //取得商品数据
        $goods_info;
        $goods_id=$snapshot['goods_id'];
        $goods_info=$Goods->get($goods_id);
        $snapshot['goods_info']=$goods_info;
        
        return $snapshot;
    }
    
}