<?php
namespace Admin\Model;
use Think\Model;
class CharRoomModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($data){
        $char_room_id=getMd5('char_room');
        $data['char_room_id']=$char_room_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        if($this->add($data)){
            return $char_room_id;
        }else{
            return false;
        }
    }
    
    public function assign($char_room_id,$admin_id){
        
        $where=[];
        $where['char_room_id']=$char_room_id;
        $save=[];
        $save['admin_id']=$admin_id;
        
        return $this->where($where)->save($save);
    }
    
    public function del($char_room_id){
        $where=[];
        $where['char_room_id']=$char_room_id;
        return $this->where($where)->delete();
    }
    
    
}