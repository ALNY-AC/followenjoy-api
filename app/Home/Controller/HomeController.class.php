<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends CommonController {
    
    // 获得首页数据包
    public function getPacket(){
        $res=[];
        
        // ===================================================
        // 创建对象
        $Carousel=D('Carousel');
        $Nav=D('Nav');
        $Msg=D('Msg');
        
        
        // 找导航数据
        $homeData=$Nav->get('0');
        
        
        // ===================================================================================
        // 组装数据
        $res['carousel']=$carousels;
        $res['homeData']=$homeData;
        
        echo json_encode($res);
        
    }
    
}