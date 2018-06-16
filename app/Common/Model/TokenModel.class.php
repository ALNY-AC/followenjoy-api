<?php
namespace Common\Model;
use Think\Model;
class TokenModel extends Model {
    
    public function create($data){
        $user_id=$data['user_id'];
        $app_secret=$data['app_secret'];
        
        // ===================================================================================
        // 检查用户是否存在
        $User=D('User');
        $where=[];
        $where['user_id']=$user_id;
        $isUser=$User->where($where)->find();
        if(!$isUser){
            // ===================================================================================
            // 没有用户，新建用户
            $data['user_id']=$user_id;
            $data['user_name']=$user_id;
            $User->create($data);
            $isUser=$User->where($where)->find();
            
        }
        
        // ===================================================================================
        // 增加用户的登录次数
        $User->where(['user_id'=>$user_id])->setInc('login_count',1); // 登录次数加+1
        
        
        $AppSecret=D('AppSecret');
        $is=$AppSecret->validate($app_secret,$user_id);
        if($is){
            // 验证成功
            // 创建token并返回，
            $token=$this->createToken($user_id);
            return $token;
        }else{
            // 验证失败
            return false;
        }
        
    }
    
    public function createToken($login_id){
        
        if(!check($login_id)){
            //如果 user_id 不存在，也就不能生成token
            return false;
        }
        
        //创建token
        $token=md5($login_id.rand().time().__KEY__);
        
        //创建要保存的数据
        $add['token']=$token;
        $add['login_id']=$login_id;
        $add['edit_time']=time();
        
        //创建模型
        //添加数据，如果存在则覆盖
        $this->add($add,null,true);
        return $token;
    }
    
    
}