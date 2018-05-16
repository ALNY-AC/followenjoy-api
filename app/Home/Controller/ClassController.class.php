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
namespace Home\Controller;
use Think\Controller;
class ClassController extends CommonController{
    
    
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
            
            
            $arr=[];
            
            
            
            foreach ($result as $key => $value) {
                
                $add=[];
                if(!$value['super_id']){
                    //没有super，代表是一级calss
                    $add=$value;
                    $add['node']=[];
                    $arr[]=$add;
                }
                if($value['super_id']){
                    //有super_id 代表是二级id，需要查找一级id，看看有没有一样的
                    for ($i=0; $i <count($arr) ; $i++) {
                        $class1=$arr[$i];
                        if($class1['class_id']==$value['super_id']){
                            $add=$value;
                            $arr[$i]['node'][]=$add;
                        }
                    }
                }
            }
            
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$arr;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    
    
}