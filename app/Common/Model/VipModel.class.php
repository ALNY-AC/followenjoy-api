<?php
namespace Common\Model;
use Think\Model;
class VipModel extends Model {
    
    public function 销售佣金奖($pay_id){
        $vip=new \VipPlus($conf);
        
        $Order=D('Order');
        $Goods=D('Goods');
        $User=D('User');
        $Snapshot=D('Snapshot');
        // ===================================================================================
        // 找订单数据
        
        
        
        $order=$Order->where(['pay_id'=>$pay_id])->select();
        
        $snapshot_ids=[];
        
        foreach ($order as $k => $v) {
            $snapshot_ids[]=$v['snapshot_id'];
        }
        
        // ===================================================================================
        // 找快照数据
        
        if($snapshot_ids){
            
            $where=[];
            $where['snapshot_id']=['in',$snapshot_id];
            $snapshot = $Snapshot->where($where)->select();
            
            // 取出所有的佣金
            
            
            foreach ($snapshot as $k => $v) {
                
                $earn_price=$v['earn_price'];//佣金
                $share_id=$v['share_id'];//分享人id
                $conf=[];
                $conf['userId']=$share_id;
                $conf['isDebug']=false;
                $conf['isSave']=true;
                $vip=new \VipPlus($conf);
                $vip->出货得佣金($earn_price);
                
                // 找找看看这个商品是不是特殊商品
                
                $where=[];
                $where['goods_id']=$v['goods_id'];
                $goods=$Goods->where($where)->find();
                
                // ===================================================================================
                // 判断是不是特殊商品
                
                if($goods['is_unique']){
                    // 是特殊商品
                    // 成为某人的下级,但是不能成为自己的下级
                    // 当分享人id和user_id是同一个人的时候不执行
                    if($v['user_id']!=$share_id){
                        // 不是同一个人的执行团队逻辑
                        // $邀请人的id,$被邀请人的id
                        $this->团队发展奖($share_id,$v['user_id']);
                    }else{
                        // 让自己成为会员
                        $where=[];
                        $where['user_id']=$v['user_id'];
                        $user=$User->where($where)->find();
                        // 先判断自己是不是会员
                        $userLevel=$user['user_vip_level']+0;
                        
                        if($userLevel==0){
                            // 成为会员
                            $save=[];
                            $save['user_vip_level']=1;
                            $User->where($whereFS)->save($save);
                        }
                        
                        
                    }
                    
                }
                
            }
            
        }
        
    }
    
    public function 团队发展奖($邀请人的id,$被邀请人的id){
        
        $conf=[];
        $conf['userId']=$邀请人的id;
        $conf['isDebug']=true;
        $conf['isSave']=false;
        $vip=new \VipPlus($conf);
        
        $conf['userId']=$被邀请人的id;
        $conf['isDebug']=true;
        $conf['isSave']=false;
        $sub=new \VipPlus($conf);
        
        $vip->获得邀人得钱奖($sub);
    }
    
    
};