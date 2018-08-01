<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月3日12:25:13
* 最新修改时间：2018年4月3日12:25:13
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####关注控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class FollowController extends CommonController{
    
    
    public function follow(){
        
        $Follow=M('Follow');
        $add['user_id']=session('user_id');
        $add['follow_id']=I('follow_id');
        $add['add_time']=time();
        $result= $Follow->add($add);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function cancelFollow(){
        
        $Follow=M('Follow');
        $where['user_id']=session('user_id');
        $where['follow_id']=I('follow_id');
        $result=  $Follow->where($where)->delete();
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