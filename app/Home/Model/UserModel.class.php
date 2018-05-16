<?php
namespace Home\Model;
use Think\Model;
class userModel extends Model {
    
    
    public function _initialize (){}
    
    public function getUpList(){
        
        $where=[];
        $where['is_up']=1;
        $upUsers=$this->where($where)->select();
        return $upUsers;
    }
    
    
    public function get($id){
        $where=[];
        $where['user_id']=$id;
        
        return $this->where($where)->find();
    }
    
    
}