<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月25日15:34:24
* 最新修改时间：2018年5月25日15:34:24
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分享模型#####
* @author 代码狮
*
*/
namespace Common\Model;
use Think\Model;
class SkuModel extends Model {
    
    // 通过快照检查库存,id为数组
    public function snapshotStockInspect($sku_ids){
        
        $where=[];
        $where['sku_id']=['in',getIds($sku_ids)];
        $skus=$this->where($where)->select();
        dump($where);
        dump($skus);
        die;
        
    }
    public function get($sku_id){
        
        $where=[];
        $where['sku_id']=$sku_id;
        return  $this->where($where)->find();
        
    }
    
    
}