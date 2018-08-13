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
        $time = strtotime('2018-8-13 20:30:00');
        
        $data['usersTotal'] = D('user')->count()+0;
        
        $user_where['add_time'] = ['gt',$time];
        $data['users'] = D('user')->where($user_where)->count()+0;

        $order_where['add_time'] = ['gt',$time];
        $data['orders'] = D('order')->where($order_where)->count()+0;

        $price_where['add_time'] = ['gt',$time];
        $data['price'] = D('order')->where($order_where)->sum('price')+0;
        echo json_encode($data);
        exit();
    }
}