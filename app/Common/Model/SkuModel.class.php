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
        
        foreach ($skus as $k => $v) {
            
            if($v['stock_num']<=0){
                // ===================================================================================
                // 没有库存啦
                echo "<h1>库存不足！</h1>";
                exit;
            }
        }
        
    }
    public function get($sku_id){
        
        $where=[];
        $where['sku_id']=$sku_id;
        return  $this->where($where)->find();
        
    }
    
    /**
    * 根据商品id取得所有的sku
    */
    public function getSkuList($goods_id){
        $where=[];
        $where['goods_id']=$goods_id;
        $skus= $this
        ->where($where)
        ->field(
        [
        'goods_id',
        'sku_id',
        'img_url',
        'id',
        'price',
        's1',
        's2',
        's3',
        'tax',
        'stock_num',
        'purchase_price',
        'earn_price',
        'supplier_id',
        'shop_code',
        'amount',
        'activity_price',
        'activity_earn_price',
        'sales_volume',
        ]
        )
        ->order('price asc,stock_num desc')
        ->select();
        return $skus;
        
    }
    /**
    * 取一个sku，价格最低的
    */
    public function getOne($goods_id){
        
        $where=[];
        $where['goods_id']=$goods_id;
        $sku= $this
        ->where($where)
        ->field(
        [
        'goods_id',
        'sku_id',
        'img_url',
        'id',
        'price',
        's1',
        's2',
        's3',
        'tax',
        'stock_num',
        'purchase_price',
        'earn_price',
        'supplier_id',
        'shop_code',
        'amount',
        'activity_price',
        'activity_earn_price',
        'sales_volume',
        ]
        )
        ->order('price asc,stock_num desc')
        ->find();
        return [$sku];
    }
    
}