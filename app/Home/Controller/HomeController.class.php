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
        // ===================================================
        // 找轮播图
        $where=[];
        $where['pages_id']=0;
        $carousels=$Carousel->getList($where);
        // ===================================================
        
        // 找导航数据
        $navList= $Nav->getList();
        $homeData=$Nav->get('0');
        $where=[];
        $where['type']=1;
        
        // ===================================================================================
        // 找消息数据
        $data=[];
        $data['where']=$where;
        $msgs=$Msg->getList($data);
        
        // ===================================================
        // 组成导航数据
        for ($i=0; $i < count($navList); $i++) {
            $nav_id=$navList[$i]['nav_id'];
            $navList[$i]['data']=$Nav->get($nav_id);
        }
        
        // ===================================================================================
        // 组装数据
        $res['msgs']=$msgs;
        $res['carousel']=$carousels;
        $res['homeData']=$homeData;
        $res['navList']=$navList;
        
        echo json_encode($res);
        
    }
    
}