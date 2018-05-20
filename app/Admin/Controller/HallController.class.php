<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月18日00:03:08
* 最新修改时间：2018年5月18日00:03:08
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####主会场控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class HallController extends CommonController{
    
    public function get(){
        
        $Hall=D('Hall');
        $data=$Hall->find();
        $res['res']=1;
        $res['msg']=$data;
        echo json_encode($res);
        
    }
    
    public function save(){
        
        $data=I('data');
        $id=1;
        $Hall=D('Hall');
        $where=[];
        $where['id']=$id;
        $result=$Hall->where($where)->save($data);
        
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