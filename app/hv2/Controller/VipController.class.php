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
        $subList=  toTime($subList);
        
        if($subList!==false){
            $res['res']=count($subList);
            $res['msg']=$subList;
        }else{
            $res['res']=-1;
            $res['msg']=$subList;
        }
        
        echo json_encode($res);
    }
    
    public function buliderShopCode(){
        
        // 如果已经生成，就不能再次生成
        $user_id=session('user_id');
        $User=D('user');
        $where=[];
        $where['user_id']=$user_id;
        $result=$User->where($where)->find();
        
        if(!$result['shop_id']){
            //不存在，可以生成
            //生成邀请码
            $code       =       rand(1,9).rand(0,9).rand(0,9).rand(0,9);
            $time       =       time();
            $time       =       substr($time,strlen()-4);
            $shop_id     =       $code.$time;
            
            $where=[];
            $where['user_id']=$user_id;
            $save=[];
            $save['shop_id']=$shop_id;
            $result=$User->where($where)->save($save);
            
            if($result){
                $res['res']=1;
                $res['msg']=$shop_id;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
            }
            
        }else{
            // 存在，不能生成
            $res['res']=-2;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
}