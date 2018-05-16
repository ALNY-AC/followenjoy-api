<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月25日01:03:15
* 最新修改时间：2018年4月25日01:03:15
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####提现控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class DrawMoneyController extends CommonController{
    
    public function getList(){
        $DrawMoney=D('DrawMoney');
        
        $data=I();
        $data['where']=getKey();
        $result=$DrawMoney->getList($data);
        $res['count']=$DrawMoney->where($data['where'])->count()+0;
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
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->get(I('draw_money_id'));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function saveData(){
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->saveData(I('draw_money_id'),I('save','',false));
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
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->del(I('ids'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getAll(){
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->getAll(I(''));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function setState(){
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->setState(I('draw_money_id'),I('state'),I('reason'));
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