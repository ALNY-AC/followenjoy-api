<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月28日13:49:32
* 最新修改时间：2018年4月28日13:49:32
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####权限组控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class PowerGroupController extends CommonController{
    
    public function creat(){
        $PowerGroup=D('PowerGroup');
        $result=$PowerGroup->creat(I('data'));
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
        $PowerGroup=D('PowerGroup');
        
        $data=I();
        $data['where']=getKey();
        
        $result=$PowerGroup->getList($data);
        
        $res['count']=$PowerGroup->where($where)->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function get(){
        $PowerGroup=D('PowerGroup');
        $result=$PowerGroup->get(I('power_group_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function del(){
        $PowerGroup=D('PowerGroup');
        $result=$PowerGroup->del(I('ids'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function saveData(){
        $PowerGroup=D('PowerGroup');
        $result=$PowerGroup->saveData(I('power_group_id'),I('data'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function getAll(){
        $PowerGroup=D('PowerGroup');
        $result=$PowerGroup->getAll(I());
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    
}