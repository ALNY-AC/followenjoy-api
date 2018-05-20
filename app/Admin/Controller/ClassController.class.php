<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class ClassController extends CommonController{
    
    /**
    * 新增
    */
    public function add(){
        
        $add=I('add');
        
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['class_id']=getMd5('class');
        
        $model=M('class');
        $result=$model->add($add);
        
        if($result){
            $res['res']=1;
            $res['msg']=$add;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    //获得列表
    public function getList(){
        
        $model=M('Class');
        $where=I('where')?I('where'):[];
        
        $result=$model
        ->where($where)
        ->order('sort asc,add_time asc')
        ->select();
        
        // =========判断=========
        
        if($result){
            
            //总条数
            $result=toTime($result);
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    public function save(){
        
        $model=M('class');
        
        $where=I('where');
        $save=I('save','',false);
        
        unset($save['class_id']);
        unset($save['add_time']);
        $save['edit_time']=time();
        
        
        $save=arrToString($save);
        $result = $model->where($where)->save($save);
        $res['msg']=$result;
        
        if($result===false){
            $res['res']=-1;
        }
        if($result>0){
            $res['res']=1;
        }
        if($result===0){
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $_class=I('class');
        
        if(empty($_class)){
            $res['res']=-1;
            echo json_encode($res);
            die;
        }
        
        $where=[];
        if($_class['super_id']){
            //二级分类
            $where['class_id']=$_class['class_id'];
        }else{
            //一级分类
            $where['class_id']=$_class['class_id'];
            $where['super_id']=$_class['class_id'];
            $where['_logic']='OR';
        }
        
        
        $model=M('class');
        //获取要删除的文件路径
        $delSrc = $model->where($where)->getField('img',true);
        $result = $model->where($where)->delete();
        
        
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            
            for ($i=0; $i <count($delSrc) ; $i++) {
                delFile($delSrc[$i]);
            }
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
}