<?php
/**
 * Created by PhpStorm.
 * User: User2
 * Date: 2018/8/13
 * Time: 18:16
 */
namespace Home\Controller;
use Think\Controller;

class UserNumController extends Controller{
    // 删掉订单中，订单中，没有快照的
    // 1、待付款
    // 2、待发货
    // 3、待收货
    // 4、交易成功
    // 5、退款/退货
    // 6、已关闭
    // 7、已退款
    // 8、退款失败
    public function getUserNum(){
        $time =strtotime(I('time'));
//所有用户量

        $data['usersTotal'] = D('user')->count()+0;
//新增户量
        $user_where['add_time'] = ['gt',$time];
        $data['users'] = D('user')->where($user_where)->count()+0;
//新增点击量
        $record['add_time'] = ['gt',$time];
        $data['record'] = D('record')->where($record)->count()+0;
//订单总量
        $order_where['add_time'] = ['gt',$time];
        $order_where['state'] = 2;
        $order_where['state'] = 3;
        $order_where['state'] = 4;
        $Order=D('Order');
        $data['orders'] = $Order->where($order_where)->count()+0;
        $sql=$Order->_sql();
//待支付订单
        $PrePriceTotal['add_time'] = ['gt',$time];
        $PrePriceTotal['state'] = 1;
        $data['PrePriceTotal'] = $Order->where($PrePriceTotal)->count()+0;

        $data['sql'] =$sql;
        $data['time'] =$time;
//金额
        $data['price'] = D('order')->where($order_where)->sum('price')+0;
//昨天新增人数
        $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
        $data['yesterday'] = D('user')->where("'add_time' > $beginYesterday AND 'add_time' < $endYesterday")->count();
        echo json_encode($data);
        exit();
    }
}