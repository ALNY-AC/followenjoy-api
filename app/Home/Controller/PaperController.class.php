<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日11:23:10
* 最新修改时间：2018年4月8日11:23:10
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####文章管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class PaperController extends CommonController{
    
    
    
    public function getPacket(){
        
        $Carousel=D('Carousel');
        $where=[];
        $where['pages_id']=2;
        $carousel=$Carousel->getList($where);
        
        $Paper=D('paper');
        $papers=$Paper->getList(I('where'));
        $res['res']=count($papers);
        $res['papers']=$papers;
        $res['carousel']=$carousel;
        
        echo json_encode($res);
        
    }
    
    
    public function getList(){
        $Paper=D('paper');
        $papers=$Paper->getList(I('where'));
        if($papers!==false){
            $res['res']=count($papers);
            $res['msg']=$papers;
        }else{
            $res['res']=-1;
            $res['msg']=$papers;
        }
        echo json_encode($res);
    }
    
    
    public function get(){
        
        $Paper=M('paper');
        $where=[];
        $where['paper_id']=I('paper_id');
        $paper= $Paper->where($where)->find();
        
        $paper=toTime([$paper],'Y-m-d')[0];
        
        if($paper){
            $res['res']=1;
            $res['msg']=$paper;
        }else{
            $res['res']=-1;
            $res['msg']=$paper;
        }
        echo json_encode($res);
        
    }
    
    
    
}