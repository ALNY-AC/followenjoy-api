<?php
namespace Home\Model;
use Think\Model;
class ShareRedRecordModel extends Model {
    
    public function create($data){
        $record_id=getMd5('record_id');
        $data['record_id']=$record_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $this->add($data);
        
        return $this->get($record_id);
        
    }
    
    public function get($record_id){
        return $this->where(['record_id'=>$record_id])->find();
    }
    
    // 领取红包
    public function pull(){
        
        /**
        * 如果 count < 6 那么可以直接领取
        * 因为人数可能是在 1~5之间，所以正在领取的人是第6个人，也可以领取
        * 如果 count == 6 那么就是第七个人模式
        *
        */
        
        
        
        /**
        * 公式：
        * if count < max_length-1
        *
        *
        */
        
        
    }
    
}