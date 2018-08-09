<?php
/**
 * Created by PhpStorm.
 * User: User2
 * Date: 2018/8/9
 * Time: 17:10
 */
namespace Home\Controller;
use Think\Controller;

class BagPlusController extends Controller {
    //购物袋数量提示
    public function getBagNum(){
        $user_id = I('user_id');
        if(!$user_id){
            $res['res']=0;
            return json_encode($res);
        }
        $Bag = D('bag');
        $where['user_id']=$user_id;
        $count = $Bag->where($where)->count()+0;
        if($count){
            $res['res']=1;
            $res['msg']=$count;
        }else{
            $res['res']=0;
        }
        return json_encode($res);
    }
}