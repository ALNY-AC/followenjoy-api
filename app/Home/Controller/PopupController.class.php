<?php
namespace Home\Controller;
use Think\Controller;
class PopupController extends Controller{
    
    
    public function getShowGoods(){
        
        $res=[];
        $res['res']=1;
        $res['msg']='';
        echo json_encode($res);
        
    }
    
}