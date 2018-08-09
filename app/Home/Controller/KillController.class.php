<?php
/**
 * Created by PhpStorm.
 * User: xukaibing
 * Date: 2018/8/6
 * Time: 9:40
 */
namespace Home\Controller;

use Think\Controller;

class KillController extends Controller
{
    //获取开场商品列表
    public function getActiveGoodsList(){
        $pageSize = 5;
        $page = I('page')?I('page'):1;
        $GoodsInfo = $this->getTimeToGoodsId($page,$pageSize);
        $goods_id = array_keys($GoodsInfo);
        $goods_time =array_values($GoodsInfo);
        $data = $this->getTitleById($goods_id,$goods_time);
        $res = $this->JsonReturn($data);
        echo json_encode($res);
    }

    //获取未开场商品列表
    public function getNextGoodsList(){
        $TimGoods = D('time_goods');//时间轴商品模型
        $UserMsg = D('user_msg');//用户消息提醒模型
        $GoodsMsg = D('msg_push');//用户商品消息提醒模型
        $pageSize = 5;
        $page = I('page',1,false);
//        $user_id = session('user_id');
        $user_id = 10086;
        $msg_id = $UserMsg->where('`user_id` ='.$user_id)->getField('msg_id',true);
        $msg_id = implode(',',$msg_id);

        $time = mktime(10,0,0,date('m'),date('d')+1,date('Y'));
        $goods_id = $TimGoods->where('`start_time` ='.$time)->limit(($page-1)*$pageSize,$pageSize)->getField('goods_id',true);

        $data = $this->getTitleById($goods_id);
        foreach ($data as $k => $v ){
            if($msg_id){
                $msg = $GoodsMsg->where('`msg_id` IN '.'(' .  $msg_id .')'. ' AND `link_id` ='.$data[$k]['goods_id'])->find();
                if($msg){
                    $data[$k]['is_warn'] = 1;
                }else{
                    $data[$k]['is_warn'] = 0;
                }
            }else{
                $data[$k]['is_warn'] = 0;
            }
            $data[$k]['end_time'] = $time;
            unset($data[$k]['stock_num']);
        }
        $res = $this->JsonReturn($data);
        echo json_encode($res);
    }

    //获取入口数据
    public function getInInfo(){
        $GoodsImg=D('goods_img');//商品图片模型
        $Sku=D('sku');//商品sku模型

        $GoodsInfo = $this->getTimeToGoodsId();
        $goods_id = array_keys($GoodsInfo);
        $goods_time =array_values($GoodsInfo);
        $data = [];
        foreach ($goods_id as $k => $v ){
            $where = [];
            $where['goods_id'] = $v;

            //取出商品的头像
            $ImgList = $GoodsImg->where($where)->order('slot asc')->field(['src'])->select();
            $data[$k]['goods_head'] = count($ImgList)>0?$ImgList[0]['src']:'';

            //取出商品对应的sku
            $skus = $Sku->where($where)->field(['price','activity_price'])->select();
            $a = $skus[0]['price'];
            $data[$k]['price'] = $skus[0]['activity_price'];
            $data[$k]['origin_price'] = $a;
            $data[$k]['end_time'] = $goods_time[$k];
            $data[$k]['time'] = time();
        }
        $p = 'price';
        $data = $this->mubbleOrder($data,$p);
        $data = array_slice($data,0,2);
        $res = $this->JsonReturn($data);
        echo json_encode($res);

    }

    //冒泡排序用于商品相应数据的从小到大排序
    public function mubbleOrder($data=[],$p=null){
        $num = count($data);
        for($i=0;$i<=$num;$i++)
        {
            for($j=$num-1;$j>$i;$j--){
                if($data[$j][$p]<$data[$j-1][$p]){
                    $temp = $data[$j];
                    $data[$j] = $data[$j-1];
                    $data[$j-1] = $temp;
                }
            }
        }
        return $data;
    }

    //判断当前时间选择不同的时间段商品
    public function getTimeToGoodsId($page=null,$pageSize=null){
        $TimGoods = D('time_goods');//时间轴商品模型
        $h = date('H',time());
        if($h>=11){
            $time = mktime(10,0,0,date('m'),date('d'),date('Y'));
        }else{
            $time = mktime(10,0,0,date('m'),date('d')-1,date('Y'));
        }
        $goods_id = $TimGoods->where('`start_time` ='.$time)->limit(($page-1)*$pageSize,$pageSize)->getField('goods_id,end_time',true);
        return $goods_id;
    }

    //获取商品信息
    public function getTitleById($goods_id=[],$goods_time=[]){
        $goods = D('goods');//商品时间模型
        $Sku=D('sku');//商品sku模型

        $where = [];
        $where['goods_id'] = ['in',getIds($goods_id)];
        $field = [
            'goods_id',
            'goods_title',
            'goods_banner',
            'sub_title',
        ];
        $data = $goods->where($where)->order('sort DESC,add_time DESC')->field($field)->select();
        foreach ($data as $k => $v ){
            $where = [];
            $where['goods_id'] = $v['goods_id'];

            //取出商品对应的sku
            $skus = $Sku->where($where)->field(['price', 'stock_num', 'activity_price'])->select();
            $a = $skus[0]['price'];
            $data[$k]['origin_price'] = $a;
            $data[$k]['price'] = $skus[0]['activity_price'];
            $data[$k]['stock_num'] = $skus[0]['stock_num'];
            $data[$k]['stock_num_total'] = 100;
            $data[$k]['end_time'] = $goods_time[$k];
            $data[$k]['time'] = time();
        }
        return $data;
    }

    //json数据返回
    public function JsonReturn($data = []){
        if($data !== false){
            $res['res'] = count($data);
            $res['msg'] = $data;
        }else{
            $res['res'] = -1;
            $res['msg'] = $data;
        }
        return $res;
    }

}