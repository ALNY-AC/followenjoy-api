<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日13:59:00
* 最新修改时间：2018年4月9日13:59:00
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####限时购控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class TimeController extends CommonController{
    
    public function getList(){
        $Time=D('Time');
        $times=$Time->getList();
        
        if($times){
            $res['res']=count($times);
            $res['msg']=$times;
        }else{
            $res['res']=-1;
            $res['msg']=$times;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        $start_time=I('start_time');
        $where=[];
        $where['start_time']=$start_time;
        $Time=D('Time');
        $result= $Time->where($where)->delete();
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
        $time=I('time');
        $time=strtotime($time);
        $Time=D('Time');
        
        $goodsIds=I('goods_id');
        $add=[];
        for ($i=0; $i < count($goodsIds); $i++) {
            
            $item['start_time']=$time;
            $item['goods_id']=$goodsIds[$i];
            $item['add_time']=time();
            $item['edit_time']=time();
            $add[]=$item;
        }
        
        $result=$Time->addAll($add);
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