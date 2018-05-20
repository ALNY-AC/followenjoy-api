<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class AdminController extends CommonController{
    
    public function creat(){
        
        $Admin=D('Admin');
        $result= $Admin->creat(I('data','',false));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        
        $admin_id=I('get.data')['admin_id'];
        $Admin=D('Admin');
        $result= $Admin->get($admin_id);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        
        $Admin=D('Admin');
        $data=I();
        $data['where']=getKey();
        $result=$Admin->getList($data);
        $res['count']=$Admin->where($data['where'])->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        $Admin=D('Admin');
        $result=$Admin->del(I('ids'));
        
        if($result){
            $res['res']=1;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function saveData(){
        
        $Admin=D('Admin');
        $result=$Admin->saveData(I('admin_id'),I('data'));
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function getUserInfo(){
        
        
        $Admin=D('Admin');
        $result=$Admin->getUserInfo();
        
        $res['admin_id']=session('admin_id');
        
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