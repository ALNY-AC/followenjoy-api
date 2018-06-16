<?php
namespace Common\Model;
use Think\Model;
class CodeModel extends Model {
    
    public function _initialize (){}
    
    public function creat($data){
        
    }
    
    // 加密验证码
    public function encryption($user_id,$user_code){
        // 加密算法： __KEY__.$user_id.$user_code.__KEY__
        $code=md5(__KEY__.$user_id.$user_code.__KEY__);
        return $code;
    }
    
    public function pushCode($user_id){
        
        $this->del($user_id);
        $code=rand(1000,9999);
        $m_code=$this->encryption($user_id,$code);
        $data['code']=$m_code;
        $data['key']=$user_id;
        $data['add_time']=time();
        
        
        if($this->add($data)){
            // 检测是否免签
            if(D('LaissezPasser')->validate($user_id)){
                $res['res']=10;
            }else{
                $result=$this->send($user_id,$code);
                $res['res']=$result;
            }
            
        }else{
            $res['res']=-2;
        }
        return   $res;
    }
    
    private function send($user_id,$code){
        return send_sms($user_id,$code);
    }
    
    public function validate($user_id,$user_code){
        
        if(D('LaissezPasser')->validate($user_id)){
            // 免签特权
            $this->del($user_id);
            return 1;
        }else{
            // 需要签证
        }
        
        $where=[];
        $where['code']=$this->encryption($user_id,$user_code);
        $where['key']=$user_id;
        
        // 验证是否存在
        $data=$this->where($where)->find();
        
        if(!$data){
            // 验证码不存在，因为验证码不正确
            return -1;
        }else{
            // 验证是否过期
            
            $time=time();
            $add_time=$data['add_time'];
            // 十分钟 超时
            if($add_time+600>$time){
                // 未超时，十分钟
                $this->del($user_id);
                // 验证正确，删除验证码
                return 1;
            }else{
                // 超时，删除验证码
                $this->del($user_id);
                return -2;
            }
        }
        return 0;
    }
    
    public function del($key){
        $where=[];
        $where['key']=$key;
        $this->where($where)->delete();
    }
    
}