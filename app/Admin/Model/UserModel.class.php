<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model {
    
    
    public function _initialize (){}
    
    public function get($user_id){
        Vendor('VIP.VIP');
        
        $where=[];
        $where['user_id']=$user_id;
        
        $user=$this->where($where)->find();
        
        //初始化用户的vip数据
        //==============================================
        //初始化vip对象
        $conf=[];
        $conf['userId']=$user_id;
        $vip=new \VIP($conf);
        $vip->setWriteDatabase(false);
        //==============================================
        $user['vip']=$vip->getInfo();//获取vip的信息
        $user=toTime([$user])[0];
        
        return $user;
    }
    
    
    public function del($ids){
        $where=[];
        $where['user_id']=['in',$ids];
        return $this->where($where)->delete();
    }
    
}