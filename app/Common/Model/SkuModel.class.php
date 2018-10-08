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
        // ->cache(true,20)
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
        // ->cache(true,20)
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
        // ===================================================================================
        // 活动价判断
        $sku=$this->getTime($sku);
        return [$sku];
    }
    
    
    // 取得限时购数据
    public function getTime($sku){
        $TimeGoods=D('TimeGoods');
        $goods_id=$sku['goods_id'];
        
        // 先取今天的
        // 没有的话再取昨天的
        // 在没有的话再取明天的
        
        $今天0点=mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $今天23点=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        // dump(date('Y-m-d H:i:s',$今天0点));
        // dump(date('Y-m-d H:i:s',$今天23点));
        
        
        
        $昨天0点=mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
        $昨天23点=mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
        // dump(date('Y-m-d H:i:s',$昨天0点));
        // dump(date('Y-m-d H:i:s',$昨天23点));
        
        
        
        $明天0点=mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
        $明天23点=mktime(23, 59, 59, date('m'), date('d')+1, date('Y'));
        // dump(date('Y-m-d H:i:s',$明天0点));
        // dump(date('Y-m-d H:i:s',$明天23点));
        
        
        $where=[];
        // 限制时间范围
        $where['start_time']=[];
        $where['start_time']=[['EGT',$今天0点],['ELT',$今天23点]];
        $where['goods_id'] = $goods_id;
        $time=$TimeGoods->where($where)->find();
        
        if(!$time){
            // 不在今天
            
            // 那就查查昨天的
            
            $where=[];
            // 限制时间范围
            $where['start_time']=[];
            $where['start_time']=[['EGT',$昨天0点],['ELT',$昨天23点]];
            $where['goods_id'] = $goods_id;
            $time=$TimeGoods->where($where)->find();
            
            if(!$time){
                // 昨天不存在
                // 那就查查明天的
                
                $where=[];
                // 限制时间范围
                $where['start_time']=[];
                $where['start_time']=[['EGT',$明天0点],['ELT',$明天23点]];
                $where['goods_id'] = $goods_id;
                $time=$TimeGoods->where($where)->find();
                
                if(!$time){
                    // 商品不在明天的时间轴上
                    // 商品不在三天时间轴上
                    $sku['is_time']=false;
                    return $sku;
                    
                }else{
                    // 商品在明天的时间抽上
                    $sku['is_time']=true;
                    $sku['test']='明天';
                }
                
            }else{
                // 商品在昨天的时间抽上
                $sku['is_time']=true;
                $sku['test']='昨天';
            }
            
        }else{
            // 在 今天
            $sku['is_time']=true;
            $sku['test']='今天';
            
        }
        
        $toTime=time();
        $start_time=$time['start_time'];
        $end_time=$time['end_time'];
        
        if( $sku['is_time']){
            
            
            $sku['original_price']=$sku['price'];
            $sku['price'] =   $sku['activity_price'];
            $sku['earn_price'] =   $sku['activity_earn_price'];
            
        }
        
        // if($toTime>$start_time && $toTime < $end_time){
        //     // 范围内
        // }
        
        
        // 检测是否还未到时间
        if($toTime<$start_time){
            // 时间还未到
            $sku['not_time']=true;
            
        }else{
            // 已经开始,或者结束，此参数不可以判断活动是否结束。
            $sku['not_time']=false;
        }
        $sku['activity_time']=$time['start_time'];
        
        
        // dump(date('Y-m-d h:i:s',$start_time));
        // dump(date('Y-m-d h:i:s',$end_time));
        // dump($goods);
        // die;
        return $sku;
    }
}