<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月12日15:10:09
* 最新修改时间：2018年8月12日15:10:09
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品组模型#####
* @author 代码狮
*
*/
namespace Admin\Model;
use Think\Model;
class GoodsGroupModel extends Model {
    
    public function copy($goods_group_id){
        
        // ===================================================================================
        // 先取出这个组的所有商品
        $GoodsGroupLink=D('GoodsGroupLink');
        $list=$GoodsGroupLink->where($where)->select();
        
        $where=[];
        $where['goods_group_id']=$goods_group_id;
        
        $group_name=$this->where($where)->getField('group_name');
        
        $newData=[];
        $newData['group_name']=$group_name.' copy';
        $newData['add_time']=time();
        $newData['edit_time']=time();
        $newData['data_status']=1;
        
        $new_goods_group_id=$this->add($newData);
        
        foreach ($list as $k => $v) {
            
            $goods_group_link_id=md5($v['goods_id'].$new_goods_group_id);
            $v['goods_group_link_id']=$goods_group_link_id;
            $v['goods_group_id']=$new_goods_group_id;
            $v['add_time']=time();
            $v['edit_time']=time();
            $v['data_status']=1;
            $list[$k]=$v;
            
        }
        
        return $GoodsGroupLink->addAll($list);
        
    }
    
}