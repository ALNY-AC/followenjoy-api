<?php
namespace Home\Model;
use Think\Model;
class MarketCouponModel extends Model {
    
    
    public function _initialize (){}
    
    
    public function get($id){
        $where=[];
        $where['market_coupon_id']=$id;
        $data=$this->where($where)->find();
        $data['coupon_group']=$this->getCouponGroups($id);
        
        return $data;
    }
    
    
    public function getAll($data){
        
        $where=[];
        // ===================================================================================
        // 创建条件，要求取得目前正在进行的活动
        $now=time();
        
        $where['start_at'] = ['lt',$now];
        $where['end_at'] = ['gt',$now];
        
        // ===================================================================================
        // 取列表
        $list=$this->where($where)->select();
        $list=$this->bulider($list);
        
        
        // ===================================================================================
        // 取用户信息
        $User=D('User');
        
        
        $userInfo=$User->get(session('user_id'));
        
        $userAddTime=$userInfo['add_time'];
        
        
        $strTime=date('Y-m-d H:i:s',$userAddTime);
        
        $arr=[];
        
        // ===================================================================================
        // c_market_user
        $MarketUser=D('MarketUser');
        
        foreach ($list as $k => $v) {
            
            $item=$v;
            $user_reg_date=$item['user_reg_date'];
            $start_at=$item['start_at'];
            $end_at=$item['end_at'];
            $market_coupon_id=$item['market_coupon_id'];
            
            // ===================================================================================
            // 判断是否已经领取
            $where=[];
            $where['user_id']=session('user_id');
            $where['market_coupon_id']=$market_coupon_id;
            
            $isAlr=$MarketUser->where($where)->find();
            if($isAlr){
                //已经领取，不能再领取
            }else{
                //还未领取，可以领取
                
                $user_type=$item['user_type'];
                
                // ec('活动名：'.$item['market_name']);
                // ec('用户注册时间：'.$userAddTime.' | '.date('Y-m-d H:i:s',$userAddTime));
                // ec('活动开始时间：'.$start_at.' | '.date('Y-m-d H:i:s',$start_at));
                // ec('活动结束时间：'.$end_at.' | '.date('Y-m-d H:i:s',$end_at));
                // ec('要求期限：'.$user_reg_date);
                
                $days=round(($start_at-$userAddTime)/3600/24) ;
                
                // ec('用户注册日期距离活动开始的天数为：'.$days);
                
                // ===================================================================================
                // 条件判断
                if($user_type=='1'){
                    //所有用户
                    // ec('活动面向用户类型： 所有用户');
                    // 所有用户直接参加
                    $arr[]=$item;
                }
                
                if($user_type=='2'){
                    //活动开始前x天内注册的用户
                    // ec('活动面向用户类型： 活动开始前x天内注册的用户');
                    // 为正数
                    if($days>=0){
                        // ec('注册时间条件满足');
                        //判断时间天数是否达标
                        
                        $days=abs($days);
                        if($days<$user_reg_date){
                            //注册天数小于限定天数，才可以通过
                            // ec('注册天数小于限定天数，可以参加此活动');
                            $arr[]=$item;
                            
                        }else{
                            // ec('注册天数大于限定天数，不可以参加此活动');
                        }
                        
                    }else{
                        // ec('注册时间条件不满足');
                    }
                    
                }
                
                if($user_type=='3'){
                    //活动开始后x天内注册的用户
                    // ec('活动面向用户类型： 活动开始后x天内注册的用户');
                    // 为负数
                    if($days<=0){
                        // ec('注册时间条件满足');
                        //判断时间天数是否达标
                        
                        $days=abs($days);
                        if($days<$user_reg_date){
                            //注册天数小于限定天数，才可以通过
                            // ec('注册天数小于限定天数，可以参加此活动');
                            $arr[]=$item;
                            
                        }else{
                            // ec('注册天数大于限定天数，不可以参加此活动');
                        }
                        
                    }else{
                        // ec('注册时间条件不满足');
                    }
                    
                }
                
                if($user_type=='4'){
                    //活动开始前后x天内注册的用户
                    // ec('活动面向用户类型： 活动开始前后x天内注册的用户');
                    // 正数负数都可以
                    $days=abs($days);
                    if($days<$user_reg_date){
                        //注册天数小于限定天数，才可以通过
                        // ec('注册天数小于限定天数，可以参加此活动');
                        $arr[]=$item;
                        
                    }else{
                        // ec('注册天数大于限定天数，不可以参加此活动');
                    }
                }
                
            }
            
            
        }
        
        
        $arr=toTime($arr);
        $arr=toTime2($arr,'Y-m-d',['end_at','start_at']);
        return $arr;
    }
    
    public function bulider($list){
        
        
        foreach ($list as $k => $v) {
            $v['coupon_group']=$this->getCouponGroups($v['market_coupon_id']);
            $list[$k]=$v;
        }
        
        return $list;
    }
    
    
    public function getCouponGroups($id){
        $MarketGroupBag=D('MarketGroupBag');
        $CouponGroup=D('CouponGroup');
        
        $where=[];
        $where['market_coupon_id']=$id;
        
        $list=$MarketGroupBag->where($where)->select();
        
        foreach ($list as $k => $v) {
            $list[$k]=$CouponGroup->get($v['coupon_group_id']);
        }
        
        return $list;
    }
    
    public function pull($market_coupon_id){
        
        $MarketUser=D('MarketUser');
        $CouponGroup=D('CouponGroup');
        $Coupon=D('Coupon');
        $data=$this->get($market_coupon_id);
        $list=$data['coupon_group'];
        foreach ($list as $k => $v) {
            $Coupon->groupToCode($v['coupon_group_id'],1,session('user_id'));
        }
        $add=[];
        $add['market_coupon_id']=$market_coupon_id;
        $add['user_id']=session('user_id');
        
        return $MarketUser->add($add,'',true);
        
    }
    
    
}