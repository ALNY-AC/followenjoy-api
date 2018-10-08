<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年9月7日10:14:11
* 最新修改时间：2018年9月7日10:14:11
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####统计#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class SourceController extends CommonController{
    
    
    public function get(){
        $Source=D('Source');
        $SourceUv=D('SourceUv');
        $SourceData=$Source->group('source_type')->getField('source_type',true);
        $SourceUvData=$SourceUv->group('source_type')->getField('source_type',true);
        
        
        $arr=[];
        
        // ===================================================================================
        // 取得没有来源的
        $where=[];
        $where['source_type']='';
        $uv_count=$SourceUv->group('ip')->where($where)->getField('ip',true);
        $uv_count=count($uv_count);
        $item=[];
        $item['name']="找不到来源";
        $item['reg_count']="0";
        $item['uv_count']=$uv_count;
        $arr[]=$item;
        
        foreach ($SourceData as $k => $v) {
            
            $item=[];
            
            $where=[];
            $where['source_type']=$v;
            $reg_count=$Source->where($where)->count();
            $uv_count=$SourceUv->group('ip')->where($where)->getField('ip',true);
            $uv_count=count($uv_count);
            
            $item['name']=$v;
            $item['reg_count']=$reg_count;
            $item['uv_count']=$uv_count;
            
            $arr[]=$item;
            
        }
        
        
        
        
        
        echo json_encode($arr);
        
    }
    
    
}