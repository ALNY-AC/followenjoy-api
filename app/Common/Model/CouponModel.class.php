<?php
namespace Common\Model;
use Think\Model;
class CouponModel extends Model {
    
    public function _initialize (){}
    
    public function 派发给新499会员大礼包($user_id){
        if(!$user_id){
            return;
        }
        $coupon=$this->取得新499会员大礼包($user_id);
        
        foreach ($coupon as $k => $v) {
            $v['add_time']=time();
            $v['edit_time']=time();
            $coupon[$k]=$v;
        }
        
        // $coupon=toTime2($coupon,'Y-m-d H:i:s',['start_at', 'end_at']);
        
        // if(false){
        //     $this->where('1=1')->delete();
        //     dump($coupon);
        // }
        
        $this->addAll($coupon);
    }
    
    public function 取得新499会员大礼包($user_id){
        if(!$user_id){
            return;
        }
        // ===================================================================================
        // 满场通用
        // 满,减,数量,有效时长,生效时间,分区
        $coupon=[];
        // 新会员券-通用-49-5
        $couponArr=$this->获得满减券(49,5,2,15,0,'','新会员券-通用-49-5');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(49,5,3,15,15,'','新会员券-通用-49-5');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(99,10,2,30,0,'','新会员券-通用-99-10');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(99,10,1,15,30,'','新会员券-通用-99-10');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(199,25,1,15,0,'','新会员券-通用-199-25');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(199,25,1,30,16,'','新会员券-通用-199-25');
        $coupon=array_merge($coupon,$couponArr);
        
        // ===================================================================================
        // 个人护理
        $couponArr=$this->获得满减券(399,50,1,30,0,'caf6f8f64d8b0d7af81299d0efeee3bb','新会员券-个人护理-399-50');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(599,50,1,30+15,15,'caf6f8f64d8b0d7af81299d0efeee3bb','新会员券-个人护理-599-50');
        $coupon=array_merge($coupon,$couponArr);
        
        // ===================================================================================
        // 彩妆
        $couponArr=$this->获得满减券(299,35,1,30,0,'9385e03d64efa66b36b46be25a7a9e3c','新会员券-彩妆-299-35');
        $coupon=array_merge($coupon,$couponArr);
        
        
        $couponArr=$this->获得满减券(499,70,1,30,15,'9385e03d64efa66b36b46be25a7a9e3c','新会员券-彩妆-499-70');
        $coupon=array_merge($coupon,$couponArr);
        
        // ===================================================================================
        // 轻奢配饰
        $couponArr=$this->获得满减券(899,150,1,30+15,0,'a24ff192de2abbb8b6c5d5a43f1f1176','新会员券-轻奢配饰-899-150');
        $coupon=array_merge($coupon,$couponArr);
        
        foreach ($coupon as $k => $v) {
            $v['user_id']=$user_id;
            $coupon[$k]=$v;
        }
        return $coupon;
    }
    
    public function 派发新用户大礼包($user_id){
        if(!$user_id){
            return;
        }
        $coupon=$this->取得新用户大礼包($user_id);
        
        foreach ($coupon as $k => $v) {
            $v['add_time']=time();
            $v['edit_time']=time();
            $coupon[$k]=$v;
        }
        // if(false){
        //     $this->where('1=1')->delete();
        //     dump($coupon);
        // }
        $this->addAll($coupon);
        
    }
    
    
    public function 取得新用户大礼包($user_id){
        if(!$user_id){
            return;
        }
        $coupon=[];
        
        // // ===================================================================================
        // // 新大礼包
        // $couponArr=$this->获得满减券(49,10,1,15,0,'','七夕券-通用-49-10');
        // $coupon=array_merge($coupon,$couponArr);
        // $couponArr=$this->获得满减券(99,20,2,15,0,'','七夕券-通用-99-20');
        // $coupon=array_merge($coupon,$couponArr);
        
        // foreach ($coupon as $k => $v) {
        //     $v['user_id']=$user_id;
        //     $coupon[$k]=$v;
        // }
        // return $coupon;
        
        // // ===================================================================================
        // // 全场通用
        
        $couponArr=$this->获得满减券(49,5,2,30,0,'','新人券-通用-49-5');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(49,5,2,30,15,'','新人券-通用-49-5');
        $coupon=array_merge($coupon,$couponArr);
        
        $couponArr=$this->获得满减券(99,10,1,15,0,'','新人券-通用-99-10');
        $coupon=array_merge($coupon,$couponArr);
        
        // // ===================================================================================
        // // 个人护理
        
        $couponArr=$this->获得满减券(150,10,1,30+15,0,'caf6f8f64d8b0d7af81299d0efeee3bb','新人券-通用-150-10');
        $coupon=array_merge($coupon,$couponArr);
        
        // // ===================================================================================
        // // 彩妆
        $couponArr=$this->获得满减券(150,10,1,30+15,0,'9385e03d64efa66b36b46be25a7a9e3c','新人券-通用-150-10');
        $coupon=array_merge($coupon,$couponArr);
        
        foreach ($coupon as $k => $v) {
            $v['user_id']=$user_id;
            $coupon[$k]=$v;
        }
        return $coupon;
    }
    
    /**
    * 生效时间:0为当天生效，30为30天后生效
    */
    public function 获得满减券($满,$减,$数量=1,$有效时长=0,$生效时间=0,$分区ID='',$卷名=''){
        
        $arr=[];
        
        for ($i=1; $i <= $数量; $i++) {
            
            $coupon=$this->取得基本模组();
            // 满减就要将折扣设置为0
            
            // ===================================================================================
            // 数值
            $coupon['discount']=0;//折扣（0为满减券）88=>8.8折
            $coupon['origin_condition']=$满;//满减条件（0为无门槛，满XX元可用）单位元
            $coupon['denominations']=$减;//减多少元  面值（0为折扣券）单位元
            
            $coupon['name']=$卷名;//优惠券名
            
            // ===================================================================================
            // 有效时间
            $start_at=mktime(0,0,0,date('m'),date('d')+$生效时间,date('Y'));//优惠券生效时间
            $end_at=mktime(0,0,0,date('m'),date('d')+$生效时间+$有效时长,date('Y'));//优惠券失效时间
            $coupon['start_at']=$start_at;
            $coupon['end_at']=$end_at;
            
            // ===================================================================================
            // 可用分区
            $coupon['class_id']=$分区ID;//分区id
            
            $arr[]=$coupon;
            
        }
        
        return $arr;
    }
    
    public function 取得基本模组(){
        $coupon=[];
        $coupon['coupon_id']=getMd5('coupon');
        $coupon['coupon_group_id']='';
        $coupon['user_id']='';
        $coupon['class_id']='';//分区id
        $coupon['name']='';
        $coupon['discount']='';//折扣（0为满减券）88=>8.8折
        $coupon['denominations']='';//面值（0为折扣券）单位元
        $coupon['origin_condition']='';//满减条件（0为无门槛，满XX元可用）单位元
        $coupon['start_at']='';
        $coupon['end_at']='';
        $coupon['reason']='';
        $coupon['value']=0.00;
        $coupon['state']=1;
        $coupon['add_time']='';
        $coupon['edit_time']='';
        return $coupon;
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
        
        if($coupon['class_id']){
            
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
                
            }else{
                $isClass=true;
            }
        }else{
            $isClass=true;
        }
        
        if($isClass){
            
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
    
    public function groupToCode($coupon_group_id,$count,$user_id = ''){
        $CouponGroup=D('CouponGroup');
        
        $data=[];
        $where=[];
        $where['coupon_group_id']=$coupon_group_id;
        $group=$CouponGroup->where($where)->find();
        
        for ($i=1; $i <= $count; $i++) {
            
            $item=[];
            $item['coupon_id']=getMd5('coupon'.$i);
            $item['coupon_group_id']=$coupon_group_id;
            $item['user_id']=$user_id;
            $item['class_id']=$group['class_id'];
            $item['name']=$group['coupon_group_name'];
            $item['discount']=$group['discount'];
            $item['denominations']=$group['denominations'];
            $item['origin_condition']=$group['origin_condition'];
            
            // 计算开始时间和结束时间，
            // 从领取时间为开始时间，领取时间就直接time()
            // 结束时间就是当前时间加上具体天数的时间戳
            
            $start_at=time();
            $end_at=mktime(0,0,0,date('m'),date('d')+$group['date'],date('Y'));
            
            $item['start_at']=$start_at;
            $item['end_at']=$end_at;
            
            $item['state']=1;
            $item['add_time']=time();
            $item['edit_time']=time();
            
            $data[]=$item;
            
        }
        
        // $coupon_group_id
        // 修改发放量

        return $this->addAll($data);
        
    }
    
    //设置使用状态
    public function setState($coupon_id,$state){
        $where=[];
        $where['coupon_id']=$coupon_id;
        $save=[];
        $save['state']=$state;
        return $this->where($where)->save($save);
    }
    
    public function getUserList($data){
        $pageSize = I('page_size',5,false);
        $page = I('page',1,false);
        $where=[];
        $where['user_id']=session('user_id');
        //        $where['user_id']=13914896237;
        
        $where['state']=['NEQ',2];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$pageSize,$pageSize)
        ->select();
        
        foreach ($list as $k => $v) {
            
            $v['end_at']=  $v['end_at']+0;
            $v['start_at']=  $v['start_at']+0;
            
            if(time() > $v['end_at']){
                //如果 + $end_time 大于现在的时间，就是没过期
                //如果 + $end_time 秒小于或者等于现在的时间，就是过期了
                //过期了
                // 使用状态
                // 0：已过期
                // 1：未使用
                // 2：已使用
                $v['state']=0;
                $this->setState($v['coupon_id'],0);
            }
            
            // 看看时间
            if(time()<$v['start_at']){
                // 还未到时间
                $v['reason']='还未到可用时间';
            }
            
            $list[$k]=$v;
        }
        
        if($data['time']){
            // $couponList=toTime($couponList);
            // $couponList=toTime2($couponList,'Y-m-d',['end_at','start_at']);
        }
        
        return $list;
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $User=D('User');
        //找用户信息
        for ($i=0; $i <count($list) ; $i++) {
            if($list[$i]['user_id']){
                $user_id=$list[$i]['user_id'];
                $where=[];
                $where['user_id']=$user_id;
                $userInfo=  $User->where($where)->find();
                $list[$i]['userInfo']=$userInfo;
            }else{
                $list[$i]['userInfo']=null;
            }
        }
        $list=toTime($list);
        $list=toTime2($list,'Y-m-d',['end_at','start_at']);
        
        return $list;
    }
}