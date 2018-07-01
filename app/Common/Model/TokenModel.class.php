<?php

namespace Common\Model;
use Think\Model;
class TokenModel extends Model {
    
    public function getToken($user_id){
        if(!$user_id){
            return false;
        }
        // ===================================================================================
        // 如果已经存在token，就直接返回已经存的，就不要重新创建token了
        
        $where=[];
        $where['login_id']=$user_id;
        $token=$this->where($where)->find();
        if($token){
            // 已经有token，检查是否过期
            $tokenTime=$token['edit_time'];
            $toTome=time();
            $end_time=2592000;
            if(($tokenTime+$end_time)>$toTome){
                //未到期
                //如果 + $end_time 大于现在的时间，就是没过期
                // 直接返回这个token
                return $token['token'];
            }else{
                // 已过期，还是要重新创建token
            }
            
        }else{
            // 没有token，需要创建新的
        }
        
        //创建token
        $token=md5($login_id.rand().time().__KEY__);
        
        // ===================================================================================
        // 保存token
        //创建要保存的数据
        $add['token']=$token;
        $add['login_id']=$user_id;
        $add['edit_time']=time();
        $this->add($add,null,true);
        return $token;
    }
    
    /**
    * 验证token是否正确
    */
    public function validate($token,$user_id){
        
        $where=[];
        $where['token']=$token;
        $where['user_id']=$user_id;
        $token=$this->where($where)->find();
        
        if($token){
            $tokenTime=$token['edit_time'];
            $toTome=time();
            $end_time=2592000;
            
            if(($tokenTime+$end_time)>$toTome){
                // 没过期
                return true;
            }else{
                // 已过期
                // 删掉
                $where=[];
                $where['token']=$token;
                $where['user_id']=$user_id;
                $token=$this->where($where)->delete();
                return false;
            }
        }else{
            //  不存在
            return false;
        }
        
    }
    
}