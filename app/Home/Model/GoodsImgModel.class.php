<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Home\Model;
use Think\Model;
class GoodsImgModel extends Model{
    
    // img_id
    // goods_id
    // src
    // slot
    // add_time
    // edit_time
    // release_time
    
    
    
    /**
    * 根据商品id取得所有图片
    */
    public function getAll($goods_id){
        
        $where=[];
        $where['goods_id']=$goods_id;
        
        $img_list=$this
        ->cache(true,60)
        ->where($where)
        ->order('slot asc')
        ->getField('src',true);
        
        $img_list=  $img_list?$img_list:[];
        
        return $img_list;
        
    }
    
    /**
    *  根据商品id取得一个图片
    */
    public function getOne($goods_id){
        
        $where=[];
        $where['goods_id']=$goods_id;
        
        $img_list=$this
        ->limit(1)
        ->cache(true,60)
        ->where($where)
        ->order('slot asc')
        ->getField('src',true);
        
        $img_list=  $img_list?$img_list:[];
        
        return $img_list;
        
    }
    
    
}