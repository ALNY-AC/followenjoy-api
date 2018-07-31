<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月25日09:39:23
* 最新修改时间：2018年7月25日09:39:23
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####展位组控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class BoothGroupController extends CommonController{
    
    public function create(){
        $BoothGroup=D('BoothGroup');
        $result=$BoothGroup->create(I('data'));
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
        $BoothGroup=D('BoothGroup');
        
        
        $result=$BoothGroup->get(I('booth_group_id'));
        
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
        $BoothGroup=D('BoothGroup');
        $result=$BoothGroup->del(I('ids'));
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
        $BoothGroup=D('BoothGroup');
        
        $data=I();
        $data['where']=getKey();
        $result=$BoothGroup->getList($data);
        $res['count']=$BoothGroup->where($data['where'])->count()+0;
        
        
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function saveData(){
        $BoothGroup=D('BoothGroup');
        $result=$BoothGroup->saveData(I('booth_group_id'),I('data'));
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    public function getAll(){
        $BoothGroup=D('BoothGroup');
        $result=$BoothGroup->getAll(I());
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