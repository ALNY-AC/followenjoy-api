<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月12日10:49:28s
* 最新修改时间：2018年4月12日10:49:28
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####规格项控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class SkuGroupPropController extends CommonController{
    
    
    public function add(){
        $SkuGroupProp=D('SkuGroupProp');
        $add=I('add');
        $sku_group_prop_id=getMd5('sku_group_prop');
        
        $add['sku_group_prop_name']=trim($add['sku_group_prop_name']);
        
        $add['sku_group_prop_id']=$sku_group_prop_id;
        $add['add_time']=time();
        $add['edit_time']=time();
        
        $result=$SkuGroupProp->add($add);
        if($result){
            $res['res']=1;
            $where=[];
            $where['sku_group_prop_id']=$sku_group_prop_id;
            $result=$SkuGroupProp->where($where)->find();
            
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        $SkuGroupProp=D('SkuGroupProp');
        $where=I('where');
        $result=$SkuGroupProp->where($where)->delete();
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