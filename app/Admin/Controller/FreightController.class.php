<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月16日15:22:12
* 最新修改时间：2018年4月16日15:22:12
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####运费模板控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class FreightController extends CommonController{
    
    //取得列表
    public function getList(){
        $Freight=D('Freight');
        $data=I();
        $data['where']=getKey();
        $result=$Freight->getList($data);
        $res['count']=$Freight->where($data['where'])->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function getAll(){
        $Freight=D('Freight');
        $data=I();
        $data['where']=getKey();
        $result=$Freight->getAll($data);
        $res['count']=$Freight->where($data['where'])->count()+0;
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    //获得单个
    public function get(){
        $Freight=D('Freight');
        $result=$Freight->get(I('freight_id','',false));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    //保存数据
    public function save(){
        $Freight=D('Freight');
        $result=$Freight->saveDate(I('freight_id','',false),I('save','',false));
        
        if($result!==false){
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
        $Freight=D('Freight');
        $result=$Freight->del(I('ids','',false));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    //新建
    public function creat(){
        $Freight=D('Freight');
        $result=$Freight->creat(I('add','',false));
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