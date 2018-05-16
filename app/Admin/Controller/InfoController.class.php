<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日17:34:58
* 最新修改时间：2018年3月2日17:34:58
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####获取信息控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class InfoController extends CommonController{
    
    
    public function getIndex(){
        
        
        
        
        // echo strtotime("-1 day")."<hr>";
        
        //1 : text: "未支付",
        //2 : text: "未发货",
        //3 : text: "已发货",
        //4 : text: "已签收",
        //5 : text: "退款/售后",
        //6 : text: "交易关闭",
        
        $order=M('order');
        $where=[];
        $where['state']=2;
        $pending=$order->where($where)->count();//待发货
        
        //待处理售后
        $where=[];
        $where['state']=5;
        $rights=$order->where($where)->count();
        
        //昨天下单数
        $Yesterday=date("Y-m-d",strtotime("-1 day"));//昨天时间戳
        $toDay=date("Y-m-d");//今天时间戳
        $where=[];
        $where['add_time'] = array( array('egt', strtotime($Yesterday)) ,array('lt',strtotime($toDay)));
        $yesterday=$order->where($where)->count();//待发货
        
        //昨日成交额
        $Yesterday=date("Y-m-d",strtotime("-1 day"));//昨天时间戳
        $toDay=date("Y-m-d");//今天时间戳
        $where=[];
        $where['add_time'] = array( array('egt', strtotime($Yesterday)) ,array('lt',strtotime($toDay)));
        $where['state'] =array('gt',1);
        $orderList=$order->where($where)->select();//昨日成交额
        
        
        $money=0;
        foreach ($orderList as $key => $value) {
            $money+=$value['money'];
        }
        
        
        $res['res']=1;
        $res['rights']=$rights;
        $res['pending']=$pending;
        $res['yesterday']=$yesterday;
        $res['money']=$money;
        
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========q
        
        
        
    }
    
}