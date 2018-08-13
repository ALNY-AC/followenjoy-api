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
    public function getUserNum(){
        $time =strtotime(I('time'));

        $data['usersTotal'] = D('user')->count()+0;

        $user_where['add_time'] = ['gt',$time];
        $data['users'] = D('user')->where($user_where)->count()+0;

        $record['add_time'] = ['gt',$time];
        $data['record'] = D('record')->where($record)->count()+0;

        $order_where['add_time'] = ['gt',$time];
        $order_where['state'] = 2;
        $Order=D('Order');
        $data['orders'] = $Order->where($order_where)->count()+0;
        $sql=$Order->_sql();

        $PrePriceTotal['state'] = 1;
        $data['PrePriceTotal'] = $Order->where($PrePriceTotal)->count()+0;

        $data['sql'] =$sql;
        $data['time'] =$time;

        $price_where['add_time'] = ['gt',$time];
        $data['price'] = D('order')->where($order_where)->sum('price')+0;
        echo json_encode($data);
        exit();
    }
}