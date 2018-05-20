<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月3日18:44:43
* 最新修改时间：2018年4月3日18:44:43
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####VIP控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class VipController extends CommonController{
    
    public function getSubList(){
        
        Vendor('VIP.VIP');
        //初始化vip对象
        $conf=[];
        $conf['userId']=session('user_id');
        $vip=new \VIP($conf);
        $vip->setDebug(false);
        $vip->setWriteDatabase(false);
        $vip->initSubList();
        $subList=  $vip->getSubList();
        if($subList){
            $res['res']=count($subList);
            $res['msg']=$subList;
        }else{
            $res['res']=-1;
            $res['msg']=$subList;
        }
        
        echo json_encode($res);
    }
    
    public function buliderInviteCode(){
        
        //生成邀请码
        $code       =       rand(0,99999);
        $time       =       time();
        $time       =       substr($time,strlen()-4);
        $invite     =       $code.$time;
        
        
        $where['user_id']=session('user_id');
        
        $User=M('user');
        $save=[];
        $save['invite_code']=$invite;
        $result=$User->where($where)->save($save);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}