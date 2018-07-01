<?php
namespace Common\Model;
use Think\Model;
class SecretModel extends Model {
    
    public function create($key){
        
        $where=[];
        $where['key']=$key;
        $secret=$this->where($where)->find();
        
        if($secret){
            return $secret['secret'];
        }else{
            $secret=getMd5($key);
            $data['secret']=$secret;
            $data['key']=$key;
            $data['add_time']=time();
            $data['edit_time']=time();
            $this->add($data);
            return $secret;
        }
        
    }
    
    public function validate($secret,$key){
        $where=[];
        $where['secret']=$secret;
        $secret_data=$this->where($where)->find();
        if($secret_data['key']===$key){
            return true;
        }else{
            return false;
        }
    }
    
    public function del($secret){
        return $this->where(['secret'=>$secret])->delete();
    }
    
}