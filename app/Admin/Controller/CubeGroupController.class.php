<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月28日11:15:08
* 最新修改时间：2018年6月28日11:15:08
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####魔方组控制器#####
* @author 代码狮
*
*/

namespace Admin\Controller;
use Think\Controller;
class CubeGroupController extends CommonController{
    
    
    public function test(){
        
        // ===================================================================================
        // 取出测试
        $CubeGroup=D('CubeGroup');
        $data=$CubeGroup->get('1850664699327bbb972e74bd598daccb');
        
        dump($data);
        die;
        
        $data=[];
        $data['cube_group_title']='测试分组';
        $data['cube_group_message']='测试备注';
        
        $CubeGroup=D('CubeGroup');
        $result=$CubeGroup->create($data);
        
        
    }
    
    public function create(){
        
        $CubeGroup=D('CubeGroup');
        $result=$CubeGroup->create(I('data'));
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
        $CubeGroup=D('CubeGroup');
        $data=I();
        $data['where']=getKey();
        $data['where']['data_status']=1;
        $result=$CubeGroup->getList(I());
        $res['count']=$CubeGroup->where($data['where'])->count()+0;
        
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
        $CubeGroup=D('CubeGroup');
        $result=$CubeGroup->getAll(I());
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
        $CubeGroup=D('CubeGroup');
        $result=$CubeGroup->get(I('id'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    public function del(){
        $CubeGroup=D('CubeGroup');
        $result=$CubeGroup->del(I('id'));
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
        $CubeGroup=D('CubeGroup');
        $result=$CubeGroup->saveData(I('id'),I('data'));
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