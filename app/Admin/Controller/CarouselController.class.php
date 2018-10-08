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
namespace Admin\Controller;
use Think\Controller;
class CarouselController extends CommonController{
    
    public function add(){
        $model=M('carousel');
        
        $add=I('add');
        $add['carousel_id']=getMd5('carousel');
        $add['add_time']=time();
        $add['edit_time']=time();
        $result=$model->add($add);
        
        if($result){
            $res['res']=1;
            
            $where=[];
            $where['carousel_id']=$add['carousel_id'];
            $carousel=$model->where($where)->find($add);
            
            $res['msg']=$carousel;
        }else{
            
            $res['res']=-1;
            $res['msg']=$result;
            
        }
        echo json_encode($res);
        
        
    }
    
    
    public function getPacket(){
        
    }
    
    
    
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
    
    
    //删除
    public function del(){
        
        $where=I('where');
        if(!$where){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $model=M('carousel');
        
        // 删除文件
        $src= $model->where($where)->field('src')->find();
        
        
        $result=$model->where($where)->delete();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
            delFile($src);
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function save(){
        
        $model=M('carousel');
        $save=I('save');
        $save['edit_time']=time();
        
        unset($save['carousel_id']);
        unset($save['add_time']);
        
        $where=I('where');
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
}