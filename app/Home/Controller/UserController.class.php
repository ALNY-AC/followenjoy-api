<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月5日11:09:123
* 最新修改时间：2018年3月5日11:09:123
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户控制器#####
* @author 代码狮
*
*/

namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController{
    
    
    public function isCode(){
        $Code=D('Code');
        $user_id=I('user_id');
        $user_code=I('user_code');
        
        $isSuccess= $Code->validate($user_id,$user_code)>0;
        
        if($isSuccess){
            $res['res']=1;
        }else{
            //验证码不正确
            $res['res']=-1;
        }
        echo json_encode($res);
        
    }
    
    
    public function setCode(){
        $pay_code=I('pay_code');
        $user_id=session('user_id');
        
        //加密算法： 用户id+密码+密匙
        $pay_code=md5($user_id.$pay_code.__KEY__);
        
        $User=D('User');
        $save=[];
        $save['pay_code']=$pay_code;
        
        $where=[];
        $where['user_id']=$user_id;
        
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
    
    
    public function save(){
        
        $model=M('user');
        $save=I('save','',false);
        $where=[];
        $where['user_id']=session('user_id');
        unset($save['user_id']);
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            $save['edit_time']=time();
            $result=$model->where($where)->save($save);
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    public function getUpList(){
        $User=D('User');
        $upUsers=$User->getUpList();
        
        if($upUsers){
            $res['res']=count($upUsers);
            $res['msg']=$upUsers;
        }else{
            $res['res']=-1;
            $res['msg']=$upUsers;
        }
        echo json_encode($res);
    }
    
    public function getUserInfo(){
        
        $field=I('field');
        
        if(!$field){
            $res['res']=-2;
            echo json_encode($res);
            exit;
        }
        
        if(gettype($field)!='array'){
            //field不是数组。不能提交
            $res['res']=-4;
            echo json_encode($res);
            exit;
        }
        
        //权限处理
        //这里是禁止访问的用户字段
        
        $arr = array(
        'user_pwd',
        );
        
        //循环检查
        foreach ($field as $key => $value) {
            if(in_array($value,$arr)){
                //当字段数组中出现禁止访问的字段
                $res['res']=-3;
                echo json_encode($res);
                exit;
            }
        }
        
        //到这一步，初步权限检测通过。
        $model=M('user');
        $where=[];
        $where['user_id']=session('user_id');
        $user=$model->where($where)->field($field)->find();
        
        //如果当前用户是会员，需要获取会员数据
        
        Vendor('VIP.VIP');
        $conf=[];
        $conf['userId']=session('user_id');
        $conf['isDebug']=false;
        $vip=new \VIP($conf);
        $vip->setWriteDatabase(false);
        $vipInfo=null;
        
        if($user['user_vip_level']>0){
            //是会员
            //初始化vip对象
            // $vip->setDebug(true);
            $vipInfo['levelName']=$vip->levelName;//会员优惠价
            $vipInfo['discount']=$vip->discount;//会员优惠价
        }else{
            //非会员可以分享商品得钱
            $vipInfo['shareMoney']=$vip->shareMoney;//会员优惠价
        }
        $user['vipInfo']=$vipInfo;
        
        if($user){
            $res['res']=1;
            $res['msg']=$user;
        }else{
            $res['res']=-1;
            $res['msg']=$user;
        }
        
        echo json_encode($res);
        
    }
    
}