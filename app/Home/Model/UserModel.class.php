<?php
namespace Home\Model;
use Think\Model;
class userModel extends Model {
    
    
    public function _initialize (){}
    
    public function getUpList(){
        
        $where=[];
        $where['is_up']=1;
        $upUsers=$this
        ->cache(true,3600)
        ->field('user_id,user_head,user_name')
        ->order('add_time desc')
        ->where($where)
        ->select();
        return $upUsers;
    }
    
    
    public function get($id){
        $where=[];
        $where['user_id']=$id;
        
        return $this->where($where)->find();
    }
    
    // 创建新用户
    public function create($data){
        
        $data['user_pwd']='';
        $data['pay_code']='';
        $data['user_head']='';
        $data['user_type']=0;
        $data['unionid']='';
        $data['user_email']='';
        $data['user_info']='';
        $data['user_vip_level']=0;
        $data['user_money']=0;
        $data['course_hours']=0;
        $data['invite_code']='';
        $data['is_up']=0;
        $data['login_count']=0;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        return $this->add($data);
        
    }
    
    public function saveData($user_id,$data){
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where(['user_id'=>$user_id])->save($data);
    }
    
}