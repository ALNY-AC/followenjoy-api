<?php
namespace Common\Model;
use Think\Model;
class CodeModel extends Model {
    
    public function _initialize (){}
    
    
    // 加密验证码
    public function encryption($phone,$user_code){
        // 加密算法： __KEY__.$phone.$user_code.__KEY__
        $code=md5(__KEY__.$phone.$user_code.__KEY__);
        return $code;
    }
    
    public function pushCode($phone){
        
        $this->del($phone);
        $code=rand(1000,9999);
        $m_code=$this->encryption($phone,$code);
        $data['code']=$m_code;
        $data['key']=$phone;
        $data['add_time']=time();
        
        if($this->add($data)){
            // 检测是否免签
            if(D('LaissezPasser')->validate($phone)){
                $res['res']=10;
            }else{
                $result=$this->send($phone,$code);
                $res['res']=$result;
            }
            
        }else{
            $res['res']=-2;
        }
        return   $res;
    }
    
    private function send($phone,$code){
        return send_sms($phone,$code);
    }
    
    public function validate($phone,$user_code){
        
        if(D('LaissezPasser')->validate($phone)){
            // 免签特权
            $this->del($phone);
            return 1;
        }else{
            // 需要签证
        }
        
        $where=[];
        $where['code']=$this->encryption($phone,$user_code);
        $where['key']=$phone;
        
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
                $this->del($phone);
                // 验证正确，删除验证码
                return 1;
            }else{
                // 超时，删除验证码
                $this->del($phone);
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