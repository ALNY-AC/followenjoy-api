<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月17日08:19:45
* 最新修改时间：2018年5月17日08:19:45
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####模板页控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class TempPagesController extends CommonController{
    
    public function get(){
        $TempPages=D('TempPages');
        $result=$TempPages->get(I('temp_pages_id'));
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
        $TempPages=D('TempPages');
        
        $data=I();
        $data['where']=getKey();
        $result=$TempPages->getList($data);
        $res['count']=$TempPages->where($data['where'])->count()+0;
        
        if($result>=0){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getAll(){
        $TempPages=D('TempPages');
        $result=$TempPages->getAll(I());
        if($result>=0){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
}