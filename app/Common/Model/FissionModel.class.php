<?php
namespace Common\Model;
use Think\Model;
class FissionModel extends Model {
    
    
    /**
    * 验证是否是499订单
    */
    public function validate($pay_id){
        
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');
        $Order=D('Order');
        $Snapshot=D('Snapshot');
        $Goods=D('Goods');
        // ===================================================================================
        // 找支付单
        
        $where=[];
        $where['pay_id']=$pay_id;
        $pay=$Pay->where($where)->find();
        
        // ===================================================================================
        // 找订单
        
        $where=[];
        $where['pay_id']=$pay_id;
        $orders=$Order->where($where)->select();
        
        
        // ===================================================================================
        // 找到快照
        
        $snapshot_ids=[];
        
        foreach ($orders as $k => $v) {
            $snapshot_ids[]=$v['snapshot_id'];
        }
        
        $where=[];
        $where['snapshot_id']=['in',$snapshot_ids];
        
        $snapshot=$Snapshot->where($where)->select();
        
        // ===================================================================================
        // 找到商品
        
        
        $goods_ids=[];
        
        foreach ($snapshot as $k => $v) {
            $goods_ids[]=$v['goods_id'];
        }
        
        $snapshot=$Snapshot->where($where)->select();
        // ===================================================================================
        // 遍历查找是否是 499
        $goods_id;
        foreach ($snapshot as $k => $v) {
            
            $is_unique=$v['is_unique'];
            if($is_unique){
                // 是
                $goods_id=$v['goods_id'];
                break ;
            }
            
        }
        
        if($goods_id){
            // 有，返回邀请人id
            // $share_id
            return $pay['share_id'];
        }else{
            // 没有
            return false;
        }
        
    }
    
    
    /**
    * 分销流程
    */
    public function handle($share_id,$user_id){
        Vendor('VIP.VIP');
        
        $UserSuper=D('UserSuper');
        $data=[];
        $data['user_id']=$user_id;
        $data['super_id']=$share_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $where['user_id']=$user_id;
        $where['super_id']=$share_id;
        $is=$UserSuper->where($where)->find();
        
        if(!$is){
            $UserSuper->add($data);
        }
        // ===================================================================================
        // 成为会员
        
        
        $User=D('User');
        $data=[];
        $data['user_vip_level']=1;
        $where['user_id']=$user_id;
        $User->where($where)->save($data);
        
        return;
        // ===================================================================================
        // 初始化vip对象
        
        $conf=[];
        $conf['userId']='13914896237';
        $conf['isDebug']=true;
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(true);
        // 上级得钱
        $super= $vip->getSuper();
        
        // ===================================================================================
        // 让上级得到【自己邀请直属会员得钱】
        $super->自己邀请直属会员得钱();
        // c_user_super
        
    }
    
}