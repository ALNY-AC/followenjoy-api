<?php
namespace Common\Model;
use Think\Model;
class VipModel extends Model {
    
    public function 销售佣金奖($order_id){
        Vendor('VIP.VipPlus');
        
        
        $Order=D('Order');
        $Goods=D('Goods');
        $User=D('User');
        $Snapshot=D('Snapshot');
        $UserSuper=D('UserSuper');
        // ===================================================================================
        // 找订单数据
        
        $order=$Order->where(['order_id'=>$order_id])->find();
        $snapshot_id=$order['snapshot_id']  ;
        // ===================================================================================
        // 找快照数据
        if($snapshot_id){
            
            $where=[];
            $where['snapshot_id']=$snapshot_id;
            $snapshot = $Snapshot->where($where)->find();//取出快照数据
            $goodsCount=$snapshot['count'];//数量
            
            $earn_price=$snapshot['earn_price'];//佣金
            $user_id=$snapshot['user_id'];//买家id
            
            $super=$UserSuper->where(['user_id'=>$user_id])->find();//取出这个用户的上级
            $super_id=$super['super_id'];//取出上级的id
            
            // ===================================================================================
            // 取出买家信息，判断买家的等级
            $where=[];
            $where['user_id']= $user_id;
            $user=$User->where($where)->find();//查找买家
            $userLevel=$user['user_vip_level']+0;//买家的vip等级
            
            // ===================================================================================
            // 判断买家的等级
            if($userLevel<=0){
                // 买家的身份为普通客户，不是会员
                // 让上级得到佣金
                if($super){
                    $conf=[];
                    $conf['userId']=$super_id;
                    $conf['isDebug']=false;
                    $conf['isSave']=true;
                    $vip=new \VipPlus($conf);
                    for ($i=1; $i <= $goodsCount ; $i++) {
                        $vip->出货得佣金($earn_price);
                    }
                }
            }else{
                // 买家身份为会员
                // 从自己开始得到佣金
                // 当前为会员
                $conf=[];
                $conf['userId']=$user_id;
                $conf['isDebug']=false;
                $conf['isSave']=true;
                $vip=new \VipPlus($conf);
                for ($i=1; $i <= $goodsCount ; $i++) {
                    $vip->出货得佣金($earn_price);
                }
            }
            
            
            // ===================================================================================
            // 判断当前商品是否为499商品
            
            $share_id=$order['share_id'];//分享人id
            
            $where=[];
            $where['goods_id']=$snapshot['goods_id'];
            $goods=$Goods->where($where)->find();
            
            if($goods['is_unique']){
                // 当前商品是499商品
                $user_id=$snapshot['user_id'];
                // ===================================================================================
                // 还要判断，如果买家已经是会员，就不能获得发展会员奖
                $where=[];
                $where['user_id']=$user_id;
                $user=$User->where($where)->find();
                $userLevel=$user['user_vip_level']+0;
                if($userLevel<=0){
                    // 买家的等级是普通客户
                    // 让买家成为会员
                    // ===================================================================================
                    // 保存买家信息
                    $save=[];
                    $save['user_vip_level']=1;
                    $User->where($where)->save($save);
                    
                    // 成为某人的下级,但是不能成为自己的下级
                    // 当分享人id和user_id是同一个人的时候不执行
                    if($user_id!=$share_id){
                        // 不是同一个人的执行团队逻辑
                        // $邀请人的id, $被邀请人的id
                        $this->团队发展奖($share_id, $user_id);
                    }
                    
                    // 发红包给新会员
                    $Coupon=D('Coupon');
                    $Coupon->派发给新499会员大礼包($user_id);
                    // 发给邀请人大礼包
                    $Coupon->派发新用户大礼包($share_id);
                }
            }
        }
    }
    
    public function 团队发展奖($邀请人的id,$被邀请人的id){
        Vendor('VIP.VipPlus');
        
        $conf=[];
        $conf['userId']=$邀请人的id;
        $conf['isDebug']=false;
        $conf['isSave']=true;
        $vip=new \VipPlus($conf);
        
        $conf['userId']=$被邀请人的id;
        $conf['isDebug']=false;
        $conf['isSave']=true;
        $sub=new \VipPlus($conf);
        
        $vip->获得邀人得钱奖($sub);
    }
    
    
    public function linkShop($user_id,$shop_id){
        
        $User=D('User');
        $where=[];
        $where['shop_id']=$shop_id;
        $shop_user=$User->where($where)->getField('user_id');
        // ===================================================================================
        // 检查绑定关系
        // 如果已经绑定，就不绑定
        // 如果没有绑定，就绑定
        if($shop_id!='-1'){
            // 只有shop_id不等于-1时才生效
            $UserSuper=D('UserSuper');
            $where=[];
            $where['user_id']=$user_id;
            $super=$UserSuper->where($where)->find();
            
            $where=[];
            $where['user_id']=$shop_user;
            $where['super_id']=$user_id;
            
            if($UserSuper->where($where)->find()){
                return;
            }else{
                // 继续
            }
            // ===================================================================================
            //
            if($super){
                // 已经绑定，不用绑定
            }else{
                
                // 未绑定，需要绑定
                // 如果这个用户存在
                if($shop_user){
                    // ===================================================================================
                    // 如果店铺号就是店主自己的，也不用绑定
                    if($shop_user!=$user_id){
                        $add=[];
                        $add['user_id']=$user_id;
                        $add['super_id']=$shop_user;
                        $add['add_time']=time();
                        $add['edit_time']=time();
                        $UserSuper->add($add);
                    }
                }
                
            }
        }
        
    }
    
};