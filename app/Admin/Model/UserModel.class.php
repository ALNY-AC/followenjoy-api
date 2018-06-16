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
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        $users=$this
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        // ===================================================================================
        // 找上级
        if($users){
            
            $UserSuper=D('UserSuper');
            
            //==============================================
            // Vendor('VIP.VIP');
            Vendor('VIP.VipPlus');
            
            //==============================================
            
            //遍历找上级
            for ($i=0; $i < count($users); $i++) {
                
                $user=$users[$i];
                $user_id=$user['user_id'];
                
                //==============================================
                //初始化vip对象
                $conf=[];
                $conf['userId']=$user_id;
                $conf['isDebug']=false;
                $conf['isSave']=true;
                $vip=new \VipPlus($conf);
                
                //==============================================
                $where=[];
                $where['user_id']=$user_id;
                $userSuper=$UserSuper->where($where)->find();//找到上级
                if($userSuper){
                    $userSuper=$this->get($userSuper['super_id']);//找上级的信息
                    $users[$i]['super']=$userSuper;//将上级信息插入到数组
                }else{
                    $users[$i]['super']=null;//将上s级信息插入到数组
                }
                
                //==============================================
                //如果上级存在， 需要初始化上级的vip对象
                $users[$i]['vip']=$vip->getInfo();//获取vip的信息
            }
            
            $users=toTime($users);
            return $users;
        }
        
        return [];
    }
    
    
}