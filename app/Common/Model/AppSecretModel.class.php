<?php
namespace Common\Model;
use Think\Model;
class AppSecretModel extends Model {
    
    public function create($key){
        
        $app_secret=getMd5($key);
        $data['app_secret']=$app_secret;
        $data['key']=$key;
        $data['add_time']=time();
        $data['edit_time']=time();
        $this->add($data);
        return $app_secret;
    }
    
    public function validate($app_secret,$key){
        
        $where=[];
        $where['app_secret']=$app_secret;
        
        $app_secret_data=$this->where($where)->find();
        
        if($app_secret_data['key']===$key){
            return true;
        }else{
            return false;
        }
        
        return false;
    }
    
    
    public function del($app_secret){
        return $this->where(['app_secret'=>$app_secret])->delete();
    }
    
    
}