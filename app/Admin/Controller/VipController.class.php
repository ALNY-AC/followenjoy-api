<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日10:55:12
* 最新修改时间：2018年4月9日10:55:12
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####vip控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class VipController extends CommonController{
    
    public function getList(){
        $Vip=D('Vip');
        $vips=$Vip->select();
        if($vips){
            $res['res']=count($vips);
            $res['msg']=$vips;
        }else{
            $res['res']=-1;
            $res['msg']=$vips;
        }
        echo json_encode($res);
    }
    
    
    public function save(){
        $Vip=D('Vip');
        $save=I('save');
        $where=I('where');
        $save['edit_time']=time();
        $result=$Vip->where($where)->save($save);
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