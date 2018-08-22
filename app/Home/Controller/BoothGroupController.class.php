<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月25日15:42:49
* 最新修改时间：2018年7月25日15:42:49
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####展位组控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class BoothGroupController extends Controller{
    
    public function get(){
        $booth_group_id=I('booth_group_id');
        $where=[];
        $where['booth_group_id']=$booth_group_id;
        
        $Booth=D('Booth');
        $result= $Booth
        ->cache(true,0)
        ->where($where)
        ->select();
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
}