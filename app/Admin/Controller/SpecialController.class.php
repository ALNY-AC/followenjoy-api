<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日18:50:25
* 最新修改时间：2018年4月8日18:50:25
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####专题控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class SpecialController extends CommonController{
    
    public function get(){
        $special_id=I('special_id');
        $Special=D('Special');
        $result=$Special->get($special_id);
        
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
        
        $Special=D('Special');
        $result=$Special->getList(I());
        $res['count']=$Special->where(I('where'))->count()+0;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
            $res['I']=I();
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function creat(){
        
        
        $Special=D('Special');
        $result=$Special->creat(I('add'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function saveData(){
        
        $Special=D('Special');
        
        $result=$Special->saveData(I('special_id'),I('save'));
        
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    // ===================================================================================
    // 文章操作
    
    
    public function addPaper(){
        
        $Special=D('Special');
        $result=$Special->addPaper(I('special_id'),I('special_paper_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    
    // ===================================================================================
    // 商品操作
    
    public function addGoods($special_id,$goods_id){
        $Special=D('Special');
        $result=$Special->addGoods(I('special_id'),I('goods_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    //删除商品
    public function delGoods(){
        
        $Special=D('Special');
        $result=$Special->delGoods(I('special_id'),I('goods_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        $Special=D('Special');
        $result=$Special->del(I('ids'));
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