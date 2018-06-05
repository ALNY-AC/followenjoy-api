<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日10:25:37
* 最新修改时间：2018年3月2日10:25:37
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户收藏控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CollectionController extends CommonController{
    
    public function change(){
        $Collection=D('Collection');
        $result=$Collection->change(I());
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
        
        $Collection=D('Collection');
        $where=[];
        $where['user_id']=session('user_id');
        
        $result=$Collection->getList(I());
        
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