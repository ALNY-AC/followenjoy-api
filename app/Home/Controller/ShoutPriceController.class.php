<?php

/**
 * Created by PhpStorm.
 * User: User2
 * Date: 2018/9/13
 * Time: 9:54
 */
namespace Home\Controller;

use Think\Exception;

class ShoutPriceController extends CommonController
{
    const PAGE_SIZE = 10;//默认每页展示数量
    // 1、待付款
    // 2、待发货
    // 3、待收货
    // 4、交易成功
    // 5、退款/退货
    // 6、已关闭
    // 7、已退款
    // 8、退款失败
    //喊价池
    public function getList()
    {
        try {
            $user_id = session('user_id');
            $page = I('pages', 1, '');
            if (!$page) {
                throw new Exception('非法请求！', '-1');
            }
            $shout_price_goods = D('shout_price_goods');
            $where['c_goods_img.slot'] = 0;
            $data = $shout_price_goods
                ->field('goods_title,s1,s2,s3,origin_price price,min_price,c_goods.goods_id,src,stock')
                ->join('left join c_goods on c_shout_price_goods.goods_id = c_goods.goods_id')
                ->join('left join c_sku on c_goods.goods_id = c_sku.goods_id')
                ->join('left join c_goods_img on c_goods.goods_id = c_goods_img.goods_id')
                ->group('c_sku.goods_id')
                ->where($where)
                ->order('c_shout_price_goods.sort desc')
                ->limit(($page - 1) * self::PAGE_SIZE, self::PAGE_SIZE)
                ->select();
                dump($data);die;
            foreach ($data as $key => $val) {
                $data[$key]['src'] = $this->img_url($data[$key]['src']);
                $data[$key]['sku_info'] = $data[$key]['s1'] . '--' . $data[$key]['s2'] . '--' . $data[$key]['s3'] . '--随享季';
                unset($data[$key]['s1'], $data[$key]['s2'], $data[$key]['s3']);
            }
            echo json_encode(['res' => 1, 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'res' => $e->getCode()]);
        }
    }

    //新建房间
    public function roomId()
    {
        try {
            $user_id = session('user_id');
            $goods_id = I('goods_id', '', '');
            if (!$goods_id && !$user_id) {
                throw new Exception('非法请求！', '-1');
            }
            $c_shout_price_room = D('shout_price_room');
            $where['owner_id'] = $user_id;
            $where['goods_id'] = $goods_id;
            $where['c_shout_price_room.end_time'] = ['gt', time()];//存在房间未过期
            //房主已购买状态
            //房间订单表
            $data = $c_shout_price_room
                ->field('c_shout_price_room.room_id,c_shout_price_order.state,c_shout_price_room.share_img')
                ->join('left join c_shout_price_order on c_shout_price_room.room_id = c_shout_price_order.room_id')
                ->where($where)
                ->order('c_shout_price_room.add_time desc')
                ->find();
            if ($data && $data['state'] == '' || $data['state'] == 1) {
                $share_info['room_id'] = $data['room_id'];
                $share_info['share_img'] = $data['share_img'];
                $share_info['share_shout_price'] = C('share_shout_price');
                echo json_encode(['res' => 1, 'data' => $share_info]);
                exit;
            }
            //房间未过期、（支付状态待支付或为空的）返回已有房间号
            if ($data['room_id'] && $data['state'] == '' || $data['state'] == 1) {
                echo json_encode(['res' => 1, 'room_id' => $data['room_id']]);
                exit;
            }
            //建房
            $goods = D('goods');
            $good_where['c_goods.goods_id'] = $goods_id;
            $good_where['c_goods_img.slot'] = 0;
            $goods_info = $goods
                ->field('goods_title,src goods_head,s1,s2,s3')
                ->join('left join c_sku on c_goods.goods_id = c_sku.goods_id')
                ->join('left join c_goods_img on c_sku.goods_id = c_goods_img.goods_id')
                ->where($good_where)
                ->find();
            $goods_info['sku_info'] = $goods_info['s1'] . '--' . $goods_info['s2'] . '--' . $goods_info['s3'] . '--随享季';
            unset($goods_info['s1'], $goods_info['s2'], $goods_info['s3']);
            $c_shout_price_goods = D('shout_price_goods');
            $shout_goods_info = $c_shout_price_goods
                ->field('min_price,origin_price,key_id,attack_price,attack_rand_max,share_img')
                ->where(['goods_id' => $goods_id])
                ->find();
            $goods_info = array_merge($goods_info, $shout_goods_info);
            $goods_info['owner_id'] = $user_id;
            $goods_info['goods_id'] = $goods_id;
            $goods_info['key_id'] = $shout_goods_info['key_id'];
            $goods_info['start_time'] = time();
            $goods_info['end_time'] = time() + 24 * 60 * 60;
            $goods_info['add_time'] = time();
            $goods_info['edit_time'] = time();
            $room_id = $c_shout_price_room->add($goods_info);
            if (!$room_id) {
                throw new Exception('房间号创建失败！', '-1');
            }
            $share_info['share_img'] = $goods_info['share_img'];
            $share_info['room_id'] = $room_id;
            $share_info['share_shout_price'] = C('share_shout_price');
            echo json_encode(['res' => 1, 'data' => $share_info]);
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'res' => $e->getCode()]);
        }
    }

    //判断是否绝对路径
    public function img_url($img_url)
    {
        if (!preg_match('/^http(s)?:\\/\\/.+/', $img_url)) {
            return C('img_url') . $img_url;
        }
        return $img_url;
    }
}