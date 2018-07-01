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
namespace Home\Controller;
use Think\Controller;
class DrawMoneyController extends CommonController{
    
    public function creat(){
        
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->creat(I('add','',false));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getList(){
        $DrawMoney=D('DrawMoney');
        $result=$DrawMoney->getList(I('','',false));
        // $result=[];
        $res['count']=$DrawMoney->count()+0;
        if($result!==false){
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
    
    
    
}