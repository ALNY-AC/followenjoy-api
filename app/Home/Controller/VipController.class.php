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
        
        $UserSuper=D('UserSuper');
        $where=[];
        $where['super_id']=session('user_id');
        $ids=$UserSuper->where($where)->getField('user_id',true);
        
        $User=D('User');
        
        $where=[];
        $where['user_id']=['in',getIds($ids)];
        $field=[
        'user_id',
        'user_name',
        'user_head',
        'user_vip_level',
        'add_time',
        ];
        
        $userList= $User->field($field)->order('add_time desc')->where($where)->select();
        
        $userList=toTime($userList);
        
        if($userList!==false){
            $res['res']=count($userList);
            $res['msg']=$userList;
        }else{
            $res['res']=-1;
            $res['msg']=$userList;
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