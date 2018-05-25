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
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        
        
        $key=I('key');
        if($key){
            
            //key存在，添加搜索条件
            //如果存在就查询
            // $key=explode(" ",$key);
            $where['user_id|user_name'] = array(
            'like',
            '%'.$key."%",
            'OR');
            $where['_logic'] = 'OR';
            
        }
        
        
        $users=$User
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $res['count']=$User
        ->where($where)
        ->order('add_time desc')
        ->count()+0;
        
        
        if($User){
            
            $UserSuper=D('UserSuper');
            
            //==============================================
            Vendor('VIP.VIP');
            
            //==============================================
            
            //遍历找上级
            for ($i=0; $i < count($users); $i++) {
                
                $user=$users[$i];
                $user_id=$user['user_id'];
                
                //==============================================
                //初始化vip对象
                $conf=[];
                $conf['userId']=$user_id;
                $conf['isDebug']=false;
                $vip=new \VIP($conf);
                $vip->setWriteDatabase(false);
                //==============================================
                $where=[];
                $where['user_id']=$user_id;
                $userSuper=$UserSuper->where($where)->find();//找到上级
                if($userSuper){
                    $userSuper=$User->get($userSuper['super_id']);//找上级的信息
                    $users[$i]['super']=$userSuper;//将上级信息插入到数组
                }else{
                    $users[$i]['super']=null;//将上s级信息插入到数组
                }
                
                //==============================================
                //如果上级存在， 需要初始化上级的vip对象
                $users[$i]['vip']=$vip->getInfo();//获取vip的信息
            }
            
            $users=toTime($users);
            $res['res']=1;
            $res['msg']=$users;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    //获得vip列表
    public function getVipList(){
        Vendor('VIP.VIP');
        
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
            $vip=new \VIP($conf);
            $vip->setWriteDatabase(false);
            $users[$i]['vip']=$vip->getInfo();//获取vip的信息
        }
        
        if($users){
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
    
    
    
    
    
    
    
}