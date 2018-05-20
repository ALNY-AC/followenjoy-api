<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月10日09:37:19
* 最新修改时间：2018年5月10日09:37:19
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####专题文章控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class SpecialPaperController extends CommonController{
    
    
    public function getList(){
        $SpecialPaper=D('SpecialPaper');
        
        $data=I();
        $result=$SpecialPaper->getList($data);
        $res['count']=$SpecialPaper->where($data['where'])->count()+0;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getAll(){
        
        $SpecialPaper=D('SpecialPaper');
        $result=$SpecialPaper->getAll(I());
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function get(){
        
        $SpecialPaper=D('SpecialPaper');
        $result=$SpecialPaper->get(I('special_paper_id'));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
}