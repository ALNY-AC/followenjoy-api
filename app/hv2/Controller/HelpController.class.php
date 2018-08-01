<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日08:29:54
* 最新修改时间：2018年4月9日08:29:54
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####帮助控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class HelpController extends Controller{
    
    
    public function getList(){
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):5;
        
        $Help=D('Help');
        $where=I('where')?I('where'):[];
        $where['is_show']=1;
        
        $result=$Help
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $result=toTime($result);
        $res['count']=$Help->where($where)->count()+0;
        
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
        
        $Help=D('Help');
        $where=I('where');
        $result=$Help->where($where)->find();
        $result=toTime([$result])[0];
        
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