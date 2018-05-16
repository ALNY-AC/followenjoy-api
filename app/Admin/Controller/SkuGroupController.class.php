<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月12日10:25:01
* 最新修改时间：2018年4月12日10:25:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####规格组控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class SkuGroupController extends CommonController{
    public function test(){
        ec('去除所有数据的前后空格');
        //规格组
        $SkuGroupProp=D('SkuGroupProp');
        $props=$SkuGroupProp->select();
        
        dump($props);
        
        foreach ($props as $key => $group) {
            
            $where=[];
            $where['sku_group_prop_id']=$group['sku_group_prop_id'];
            $save=[];
            $save['sku_group_prop_name']=trim($group['sku_group_prop_name']);
            $SkuGroupProp->where($where)->save($save);
            
        }
        
        $props=$SkuGroupProp->select();
        dump($props);
        
        
    }
    
    public function getList(){
        
        $SkuGroup=D('SkuGroup');
        
        $skuGroups=$SkuGroup->getList();
        
        if($skuGroups!==false){
            $res['res']=count($skuGroups);
            $res['msg']=$skuGroups;
        }else{
            $res['res']=-1;
            $res['msg']=$skuGroups;
        }
        echo json_encode($res);
        
    }
    
    //添加规格组
    public function add(){
        
        $SkuGroup=D('SkuGroup');
        $add=I('add');
        $sku_group_id=getMd5('sku_group');
        
        $add['sku_group_name']=trim($add['sku_group_name']);
        
        $add['sku_group_id']=$sku_group_id;
        $add['add_time']=time();
        $add['edit_time']=time();
        $result=$SkuGroup->add($add);
        if($result){
            $res['res']=1;
            $result=$SkuGroup->get($sku_group_id);
            
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function save(){
        $SkuGroup=D('SkuGroup');
        $save=I('save');
        $where=I('where');
        $save['edit_time']=time();
        
        $save['sku_group_name']=trim($save['sku_group_name']);
        
        $result=$SkuGroup->where($where)->save($save);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        $SkuGroup=D('SkuGroup');
        $SkuGroupProp=D('SkuGroupProp');
        
        $where=I('where');
        //删除组
        $result=$SkuGroup->where($where)->delete();
        if($result!==false){
            //删除项
            $result=$SkuGroupProp->where($where)->delete();
            if($result!==false){
                $res['res']=1;
                $res['msg']=$result;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
            }
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}