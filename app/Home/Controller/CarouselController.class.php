<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月5日11:47:04
* 最新修改时间：2018年3月5日11:47:04
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####轮播图控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CarouselController extends CommonController{
    
    
    public function getList(){
        
        $model=M('carousel');
        $result=$model
        ->order('sort asc,add_time desc')
        ->select();
        
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