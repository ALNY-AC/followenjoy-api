<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月15日21:49:55
* 最新修改时间：2018年4月15日21:49:55
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####供货商控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class SupplierController extends CommonController{
    
    
    public function getList(){
        $Supplier=D('Supplier');
        
        $data=I();
        $where=$data['where'];
        $key=$data['key'];
        if($key){
            
            $keys=$key;
            //先根据空格分割为数组
            $keys = explode(" ", $keys);
            $keys = array_filter($keys);  // 删除空元素
            
            foreach ($keys as $k => $v) {
                $keys[$k]='%'.$v.'%';
            }
            $group=$data['group'];
            $where[$group]=['like',$keys,'OR'];
            
        }
        $data['where']=$where;
        
        
        $result=$Supplier->getList($data);
        $res['count']=$Supplier->where($where)->count()+0;
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        $Supplier=D('Supplier');
        $result=$Supplier->del(I('ids'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function add(){
        $Supplier=D('Supplier');
        $add=I('add');
        $result=$Supplier->create($add);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            //创建失败
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        //已经存在
        if($result==='is no null'){
            $res['res']=-2;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function get(){
        $Supplier=D('Supplier');
        $result=$Supplier->get(I('supplier_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function save(){
        
        $Supplier=D('Supplier');
        $supplier_id=I('supplier_id');
        $save=I('save');
        $where=[];
        $where['supplier_id']=$supplier_id;
        $result=$Supplier->saveData($where,$save);
        
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