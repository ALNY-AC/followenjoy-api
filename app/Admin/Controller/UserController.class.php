<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月7日10:14:54
* 最新修改时间：2018年3月7日10:14:54
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
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController{
    
    public function getList(){
        
        $User=D('User');
        
        $data=I();
        $data['where']=getKey();
        $result=$User->getList($data);
        $res['count']=$User->where($data['where'])->count()+0;
        
        if($result>=0){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        // ===================================================================================
        
        
    }
    
    //获得vip列表
    public function getVipList(){
        Vendor('VIP.VipPlus');
        
        $User=D('User');
        $where=[];
        $where['user_vip_level']=array('gt',0);
        $users=$User->where($where)->select();
        
        for ($i=0; $i < count($users); $i++) {
            $user=$users[$i];
            $user_id=$user['user_id'];
            //==============================================
            //初始化vip对象
            
            $conf=[];
            $conf['userId']=$user_id;
            $conf['isDebug']=false;
            $conf['isSave']=true;
            $vip=new \VipPlus($conf);
            $users[$i]['vip']=$vip->getInfo();//获取vip的信息
            
        }
        
        if($users!==false){
            $res['res']=count($users);
            $res['msg']=$users;
        }else{
            $res['res']=-1;
            $res['msg']=$users;
        }
        echo json_encode($res);
        
    }
    
    public function creat(){
        $data=I('data');
        if(!$data){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        //先看看id有没有重复
        
        $model=M('user');
        $user_id=$add['user_id'];
        
        $where['user_id']=$user_id;
        $isUser=$model->where($where)->find();
        
        
        if(!$isUser){
            //没有
            $data['add_time']=time();
            $data['edit_time']=time();
            $result=$model->add($data);
            if($result){
                //添加成功
                $res['res']=1;
            }else{
                //添加失败
                $res['res']=-1;
                $res['msg']=$result;
            }
            
            
        }else{
            //有了这个用户
            $res['res']=-3;
        }
        echo json_encode($res);
        
    }
    
    public function saveData(){
        $User=D('User');
        $where=[];
        $where['user_id']=I('user_id');
        $data=I('data','',false);
        unset($data['add_time']);
        $data['edit_time']=time();
        $result= $User->where($where)->save($data);
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function save(){
        
        $model=M('user');
        $where=I('where');
        $save=I('save');
        if(!$where || !$save){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        
        $save['edit_time']=time();
        
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            $result=$model->where($where)->save($save);
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function getAll(){
        $User=D('User');
        $result=$User->where(I('where'))->select();
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        
        $User=D('User');
        $result=$User->del(I('ids'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    
    // ===================================================================================
    // 获取用户详细信息，更详细的信息
    public function info($user_id){
        
        $User=D('User');
        // ===================================================================================
        // 取得基本信息
        $where=[];
        $where['user_id']=$user_id;
        $user=$User->where($where)->find();
        
        
        // ===================================================================================
        // 取得累计收益信息 1
        
        
        // ===================================================================================
        // 取得地址信息 1
        
        
        // ===================================================================================
        // 取得购物车信息
        
        
        // ===================================================================================
        // 取得收藏信息
        
        
        
        // ===================================================================================
        // 取得订单信息 1
        
        
        // ===================================================================================
        // 取得下级列表信息 1
        
        
        
        // ===================================================================================
        // 取得数据统计信息 1
        
        
        if($user){
            $res['res']=count($user);
            $res['msg']=$user;
        }else{
            $res['res']=-1;
            $res['msg']=$user;
        }
        echo json_encode($res);
    }
    
}