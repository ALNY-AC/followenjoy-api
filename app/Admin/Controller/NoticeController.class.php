<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月31日09:27:17
* 最新修改时间：2018年8月31日09:27:17
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

namespace Admin\Controller;
use Think\Controller;
class NoticeController extends CommonController{
    
    
    public function getList(){
        
        $Notice=D('Notice');
        
        
        $page=I('page');
        $page_size=I('page_size');
        
        
        $where=[];
        
        $noticeList  =  $Notice
        ->order('add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$page_size)
        ->select();
        
        $noticeList=toTime($noticeList,'Y-m-d H:i:s',['edit_time']);
        
        $res['total']=$Notice
        ->where($where)
        ->count()+0;
        
        
        if($noticeList){
            $res['res']=count($noticeList);
            $res['msg']=$noticeList;
        }else{
            $res['res']=-1;
            $res['msg']=$noticeList;
        }
        echo json_encode($res);
        
    }
    
    public function setDone(){
        $Notice=D('Notice');
        $result=$Notice->setDone(I('notice_id'),I('is_done'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}