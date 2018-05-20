<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月23日17:20:18
* 最新修改时间：2018年4月23日17:20:18
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####动态控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class DynamicController extends CommonController{
    
    
    //添加
    public function creat(){
        $Dynamic=D('Dynamic');
        $result=$Dynamic->creat(I('add'));
        // 判断
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    //获得列表
    public function getList(){
        
        $Dynamic=D('Dynamic');
        
        $data=I();
        $data['where']=getKey();
        $result=$Dynamic->getList($data);
        $res['count']=$Dynamic->where($data['where'])->count()+0;
        
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function get(){
        
        $Dynamic=D('Dynamic');
        $result=$Dynamic->get(I('dynamic_id'));
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
        
        $Dynamic=D('Dynamic');
        $result=$Dynamic->del(I('ids'));
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
        $Dynamic=D('Dynamic');
        $result=$Dynamic->svaeData(I('dynamic_id'),I('save'));
        // 判断
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function setShow(){
        $Dynamic=D('Dynamic');
        $is_show=I('is_show');
        $dynamic_id=I('dynamic_id');
        $where=[];
        $where['dynamic_id']=$dynamic_id;
        
        
        $save=[];
        $save['is_show']=$is_show;
        
        $result=   $Dynamic->where($where)->save($save);
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