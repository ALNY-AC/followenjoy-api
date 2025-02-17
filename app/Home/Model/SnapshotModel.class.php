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
namespace Home\Model;
use Think\Model;
class SnapshotModel extends Model {
    
    public function _initialize (){}
    
    //保存一个优惠券给快照
    public function saveCoupon($snapshot_id,$coupon_id){
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $save=[];
        $save['coupon_id']=$coupon_id;
        
        $Coupon=D('Coupon');//优惠券
        //判断是否可以使用
        $discount=$Coupon->getCouponPrice($coupon_id,$snapshot_id,false);
        if($discount>0){
            //可以使用
            $result=$this->where($where)->save($save);
            return $result;
        }else{
            //不可以使用
            return false;
        }
    }
    
    //添加一个快照信息
    public function create($goods_id,$sku_id,$count=1,$share_id=''){
        
        $Sku=D('Sku');
        $Goods=D('goods');
        //==================================================================
        //如果有的order_id 为null，则直接返回这个null 的数据
        $user_id=session('user_id');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['sku_id']=$sku_id;
        $where['user_id']=$user_id;
        $where['order_id']=['EXP','is NULL'];
        $snapshot=$this->where($where)->find();
        //==================================================================
        //如果添加过就不用再次添加，需要更新数据
        if(!$snapshot){
            //未添加
            //==================================================================
            //取得sku
            $where=[];
            $where['sku_id']=$sku_id;
            $sku=$Sku->where($where)->find();
            //==================================================================
            //取得商品
            $where=[];
            $where['goods_id']=$goods_id;
            $goods=$Goods->get($goods_id,['img_list']);
            //==================================================================
            //组成添加数据
            $goods_title=$goods['goods_title'];
            $img=$goods['img_list'][0];
            $snapshot_id=getMd5('snapshot');
            $add=[];
            $add['snapshot_id']=$snapshot_id;
            $add['goods_id']=$goods_id;
            $add['img']=$img['src'];
            $add['goods_title']=$goods_title;
            $add['sku_id']=$sku_id;
            $add['s1']=$sku['s1'];
            $add['s2']=$sku['s2'];
            $add['s3']=$sku['s3'];
            $add['tax']=$sku['tax'];
            $add['price']=$sku['price'];
            
            $add['is_unique']=$goods['is_unique'];//是否是499商品
            $add['is_cross_border']=$goods['is_cross_border'];//是否是跨境
            
            // 加分享id
            $add['share_id']=$share_id;//share_id
            
            $add['earn_price']=$sku['earn_price'];
            $add['purchase_price']=$sku['purchase_price'];
            $add['shop_code']=$sku['shop_code'];
            $add['amount']=$sku['amount'];
            
            // 限时购字段
            $add['activity_price']=$sku['activity_price'];//活动时价格
            $add['activity_earn_price']=$sku['activity_earn_price'];//活动时佣金
            
            $add['user_id']=session('user_id');
            $add['count']=$count+0;
            $add['add_time']=time();
            $add['edit_time']=time();
            //==================================================================
            //添加到数据库中
            $snapshot=$this->add($add);
        }else{
            //已添加，返回已添加的id，并且追加数量，需要更新数据
            
            $snapshot=$this->updateGoods($snapshot);
                
            $snapshot_id=$snapshot['snapshot_id'];
            $where=[];
            $where['snapshot_id']=$snapshot_id;
            $save=[];
            $save['count']=$count+0;
            $save['share_id']=$share_id;
            
            $this->where($where)->save($save);
        }
        
        return $snapshot_id;
    }
    
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
        
        $snapshot=$this->updateGoods($snapshot);
        $snapshot=$this->getSku($snapshot);
        $snapshot=$this->getTime($snapshot);//取得限时购数据
        
        return $snapshot;
    }
    
    public function getSku($snapshot){
        if(!$snapshot['no_sku']){
            $Sku=D('Sku');
            $sku=$Sku->get($snapshot['sku_id']);
            $snapshot['sku']=$sku;
        }
        return $snapshot;
        
    }
    
    public function getList($snapshot_ids){
        $arr=[];
        foreach ($snapshot_ids as $key => $id) {
            $arr[]=$this->get($id);
        }
        return $arr;
    }
    
    // 获得限时购数据
    public function getTime($snapshot){
        
        $TimeGoods=D('TimeGoods');
        
        $goods_id=$snapshot['goods_id'];
        
        $where=[];
        // 限制时间范围
        // strtotime("-1 day")
        // strtotime("+1 day")
        $where['start_time']=[];
        $where['start_time'] = [['EGT',strtotime("-1 day")],['ELT',strtotime("+1 day")]];
        $where['goods_id'] = $goods_id;
        
        $time=$TimeGoods->where($where)->find();
        if(!$time){
            // 不在时间轴上
            $snapshot['is_time']=false;
            return $snapshot;
        }else{
            $snapshot['is_time']=true;
        }
        
        $toTime=time();
        $start_time=$time['start_time'];
        $end_time=$time['end_time'];
        // f_start_time 将时间轴开始日期格式化
        $time['f_start_time']=date('m月d日 H:i',$start_time);
        
        if($toTime>$start_time && $toTime < $end_time){
            // 限时购商品，正在进行时
            $snapshot['original_price']=$snapshot['price'];
            $snapshot['price'] =   $snapshot['activity_price'];
            $snapshot['earn_price'] =   $snapshot['activity_earn_price'];
        }
        
        // 检测是否还未到时间
        if($toTime<$start_time){
            // 时间还未到
            $snapshot['not_time']=true;
        }else{
            // 已经开始,或者结束，此参数不可以判断活动是否结束。
            $snapshot['not_time']=false;
        }
        
        $snapshot['time']=$time;
        
        return $snapshot;
    }
    
    // 同步一下数据
    public function updateGoods($snapshot){
        
        if($snapshot['order_id']){
            // 已经存在订单，就不用再同步
            return;
        }
        
        $sku_id=$snapshot['sku_id'];
        $Sku=D('Sku');
        
        $where=[];
        $where['sku_id']=$sku_id;
        $sku=$Sku->where($where)->find();
        if(!$sku){
            // sku不存在了
            $snapshot['no_sku']=true;
            return $snapshot;
        }else{
            // sku还存在
            $snapshot['no_sku']=false;
        }
        
        $data=[];
        $data['is_unique']=$sku['is_unique'];//是否是499商品
        $data['earn_price']=$sku['earn_price'];
        $data['purchase_price']=$sku['purchase_price'];
        $data['shop_code']=$sku['shop_code'];
        $data['amount']=$sku['amount'];
        $data['activity_price']=$sku['activity_price'];//活动时价格
        $data['activity_earn_price']=$sku['activity_earn_price'];//活动时佣金
        
        $where=[];
        $where['snapshot_id']=$snapshot['snapshot_id'];
        $this->where($where)->save($data);
        
        foreach ($data as $k => $v) {
            $snapshot[$k]=$v;
        }
        return $snapshot;
    }
    
}