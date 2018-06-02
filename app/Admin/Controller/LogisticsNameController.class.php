<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月1日11:14:451
* 最新修改时间：2018年6月1日11:14:451
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####物流名称#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class LogisticsNameController extends CommonController{
    
    public function getList(){
        $LogisticsName=D('LogisticsName');
        
        $result=$LogisticsName->getList();
        
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