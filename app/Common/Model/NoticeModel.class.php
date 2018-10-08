<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月30日22:56:53
* 最新修改时间：2018年8月30日22:56:53
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####消息通知#####
* @author 代码狮
*
*/
namespace Common\Model;
use Think\Model;
class NoticeModel extends Model {
    
    
    /**
    * 发送方
    * 接收方
    * 标题
    * 信息
    * 内容
    */
    public function send($send_id='root',$rece_id='',$title='',$info='',$content='',$link_type='',$link_id='',$warn_level=0){
        
        $key=md5($send_id.$rece_id.$title.$info.$content.$link_type.$link_id.$warn_level);
        
        $where=[];
        $where['key']=$key;
        
        if(!$this->where($where)->find()){
            
            $data=[];
            $data['notice_id']=uniqid();
            $data['key']=$key;
            $data['send_id']=$send_id;
            $data['rece_id']=$rece_id;
            $data['title']=$title;
            $data['info']=$info;
            $data['content']=$content;
            $data['count']=1;
            $data['is_read']=0;
            $data['is_done']=0;
            $data['warn_level']=$warn_level;
            $data['add_time']=time();
            $data['edit_time']=time();
            
            return $this->add($data);
            
        }else{
            $where=[];
            $where['key']=$key;
            return $this->where($where)->setInc('count',1);
        }
        
    }
    
    
    public function setRead($notice_id,$is_read){
        
        $data=[];
        $data['is_read']=$is_read;
        $data['edit_time']=time();
        
        $where=[];
        $where['notice_id']=$notice_id;
        
        return  $this->where($where)->save($data);
        
    }
    
    public function setDone($notice_id,$is_done){
        
        $data=[];
        $data['is_done']=$is_done;
        $data['edit_time']=time();
        
        $where=[];
        $where['notice_id']=$notice_id;
        
        return  $this->where($where)->save($data);
        
    }
    
    public function get($notice_id){
        
        $where=[];
        $where['notice_id']=$notice_id;
        return  $this->where($where)->find($data);
        
    }
    
    
    
    
}