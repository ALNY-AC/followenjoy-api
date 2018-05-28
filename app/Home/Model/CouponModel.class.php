<?php
namespace Home\Model;
use Think\Model;
class CouponModel extends Model {
    
    
    public function _initialize (){}
    
    //设置使用状态
    public function setState($coupon_id,$state){
        $where=[];
        $where['coupon_id']=$coupon_id;
        $save=[];
        $save['state']=$state;
        return $this->where($where)->save($save);
    }
    
    public function getList($data){
        
        $where=[];
        $where['user_id']=session('user_id');
        $couponList  =  $this
        ->order('add_time desc')
        ->where($where)
        ->select();
        
        //过期检测
        for ($i=0; $i <count($couponList) ; $i++) {
            $item=$couponList[$i];
            
            if(time() > $item['end_at']){
                //如果 + $end_time 大于现在的时间，就是没过期
                //如果 + $end_time 秒小于或者等于现在的时间，就是过期了
                //过期了
                // 使用状态
                // 0：已过期
                // 1：未使用
                // 2：已使用
                $couponList[$i]['state']=0;
                $this->setState($item['coupon_id'],0);
            }
            // dump(time() > $item['end_at']);
            // dump($item['state']);
            // dump($item['value']);
            // echo '<hr>';
        }
        
        if($data['time']){
            $couponList=toTime($couponList);
            $couponList=toTime2($couponList,'Y-m-d',['end_at','start_at']);
        }
        
        return $couponList;
    }
    
    /**
    * 获得优惠券减去的价格，当优惠券的信息存在，此函数会将优惠券信息写入到关联表中
    * @param String $coupon_id 优惠券的id
    * @param Array $orders 商品快照数组
    * @param Nmuber $total 订单总价
    * @return 返回促销活动可以减去的价格，如果不满足条件就返回0，否则返回可优惠的价格
    */
    public function getCouponPrice($coupon_id,$orders,$snapshots,$total){
        if(!$coupon_id) return 0;// 没有优惠券id，直接返回0，也就是不优惠
        
        // ===================================================================================
        // 创建模型
        $Goods=D('Goods');
        $OrderCoupon=D('OrderCoupon');
        
        // ===================================================================================
        // 获得优惠券的数据
        $where=[];
        $where['coupon_id']=$coupon_id;
        $coupon=$this->where($where)->find();
        
        // ===================================================================================
        // 判断优惠券的条件
        
        foreach ($snapshots as $key => $value) {
            // ===================================================================================
            // 取得商品数据
            $where=[];
            $where['goods_id']=$value['goods_id'];
            $goods=$Goods->where($where)->find();
            
            $class_id=$goods['goods_class'];
            $order_id=$value['order_id'];
            
            // 验证优惠
            $price=$this->validate($coupon,$class_id,$total);
            if($price===false){
                //已过期
                $this->setState($coupon_id,0);
                return 0;
            }
            // 找到order_id
            if($price>0){
                // 验证通过，记录后直接返回
                // 记录到数据库，并且设置此优惠券使用状态
                // ===================================================================================
                // 将优惠券写入到数据库中，如果$order_id存在，且不为false
                
                $add['order_coupon_id']=getMd5('order_coupon');
                $add['order_id']=$order_id;
                $add['coupon_id']=$coupon_id;
                $add['price']=$price;
                $add['add_time']=time();
                $add['edit_time']=time();
                $OrderCoupon->add($add);
                
                //让优惠券使用
                // 使用状态
                // 0：已过期
                // 1：未使用
                // 2：已使用
                $this->setState($coupon_id,2);
                return $add;
            }
        }
        
        // ===================================================================================
        // 到这里就代表优惠券不能使用
        return 0;
    }
    
    /**
    * 验证优惠券是否可以使用
    * @param Object $coupon 优惠券的数据
    * @param String $class_id 当前商品的分区id，用于判断分区
    * @param Nmuber $total 订单总价
    * @return 返回大于0，则代表可以优惠，并且为可优惠的金额，返回小于等于0，为不可以优惠
    */
    private function validate($coupon, $class_id, $total){
        $Class=D('Class');
        
        // 先判断商品分区（类型）
        //判断是否过期或者已经使用
        if($coupon['state']==2 || $coupon['state']==0){
            //已使用或者已过期
            return 0;
        }
        
        if(time() > $coupon['end_at']){
            //已过期
            //如果 + $end_time 大于现在的时间，就是没过期
            //如果 + $end_time 秒小于或者等于现在的时间，就是过期了
            //过期了
            // 使用状态
            // 0：已过期
            // 1：未使用
            // 2：已使用
            $coupon['state']=0;
            return false;
        }
        
        
        
        if($coupon['class_id']!=$class_id){
            //分区不正确，直接返回0。
            // 找这个分区的上级分区，看看对不对
            $class=$Class->where(['class_id'=>$class_id])->find();
            $super_id=$class['super_id'];
            
            
            // 有上级
            // 判断上级的class
            if($coupon['class_id']==$super_id){
                // 分区正确
                $isClass=true;
            }else{
                // 分区不正确
                return 0;
            }
            
        }
        
        if(!$coupon['class_id'] || $isClass){
            
            // 判断是不是满减券
            if($coupon['denominations']>0){
                
                // 是满减券
                // 判断是不是有满减门槛
                if($coupon['origin_condition']>0){
                    // 有满减门槛
                    // 判断当前订单满减门槛是否满足
                    
                    if ($total > $coupon['origin_condition'] ) {
                        // 计算满减数据
                        // 返回满减的值
                        
                        return $coupon['denominations'] ;
                    }else{
                        // 订单不满足当前门槛
                        return 0;
                    }
                    
                }else{
                    // 没有满减门槛
                    return $coupon['denominations'] ;
                }
                
            }else{
                
                // 这里是折扣券，打折
                return $total - $total * ($coupon['discount'] / 100);
            }
            
        }
        
        //如果到这里，就代表这个券不满足当前产品
        return 0;
    }
    
    public function groupToCode($coupon_group_id,$count,$user_id){
        $CouponGroup=D('CouponGroup');
        
        $data=[];
        $where=[];
        $where['coupon_group_id']=$coupon_group_id;
        $group=$CouponGroup->where($where)->find();
        
        // ===================================================================================
        // 减库存操作
        $CouponGroup->where($where)->setDec('stock',$count);
        
        // ===================================================================================
        // 生成指定数量
        for ($i=1; $i <= $count; $i++) {
            
            
            // stock
            
            $item=[];
            $item['coupon_id']=getMd5('coupon'.$i);
            $item['coupon_group_id']=$coupon_group_id;
            $item['user_id']=$user_id;
            $item['class_id']=$group['class_id'];
            $item['name']=$group['coupon_group_name'];
            $item['discount']=$group['discount'];
            $item['denominations']=$group['denominations'];
            $item['origin_condition']=$group['origin_condition'];
            $item['start_at']=$group['start_at'];
            $item['end_at']=$group['end_at'];
            $item['state']=1;
            $item['add_time']=time();
            $item['edit_time']=time();
            $data[]=$item;
        }
        
        return   $this->addAll($data);
        
    }
    
}