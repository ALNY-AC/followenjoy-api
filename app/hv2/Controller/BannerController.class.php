<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月17日19:57:34
* 最新修改时间：2018年5月17日19:57:34
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####banner控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class BannerController extends Controller{
    
    
    public function get(){
        $Banner=D('Banner');
        $result=$Banner->get(I('banner_id'));
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
        $Banner=D('Banner');
        
        $data=I();
        $data['where']=getKey();
        $result=$Banner->getList($data);
        $res['count']=$Banner->where($data['where'])->count()+0;
        
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
        $Banner=D('Banner');
        
        $data=I();
        $data['where']=getKey();
        $result=$Banner->getAll($data);
        $res['count']=$Banner->where($data['where'])->count()+0;
        
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