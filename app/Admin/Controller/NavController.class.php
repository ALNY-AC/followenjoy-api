<?php

/**
 * +----------------------------------------------------------------------
 * 创建日期：2018年4月8日16:47:25
 * 最新修改时间：2018年4月8日16:47:25
 * +----------------------------------------------------------------------
 * https：//github.com/ALNY-AC
 * +----------------------------------------------------------------------
 * 微信：AJS0314
 * +----------------------------------------------------------------------
 * QQ:1173197065
 * +----------------------------------------------------------------------
 * #####导航控制器#####
 * @author 代码狮
 *
 */
namespace Admin\Controller;

use Think\Controller;

class NavController extends CommonController
{

    public function getList()
    {

        $Nav = D('Nav');
        $result = $Nav->getList();

        if ($result !== false) {
            $res['res'] = count($result);
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);

    }

    public function create()
    {

        $nav_id = getMd5('nav');
        $Nav = D('Nav');
        $data = I('data');
        $data['nav_id'] = $nav_id;
        $data['add_time'] = time();
        $data['edit_time'] = time();
        $result = $Nav->add($data);

        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);
    }

    public function add()
    {
        $nav_id = getMd5('nav');
        $Nav = D('Nav');
        $add = I('add');
        $add['nav_id'] = $nav_id;
        $add['add_time'] = time();
        $add['edit_time'] = time();
        $result = $Nav->add($add);

        if ($result) {
            $res['res'] = 1;
            $where = [];
            $where['nav_id'] = $nav_id;
            $nav = $Nav->where($where)->find();

            $res['msg'] = $nav;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);

    }
    public function get()
    {

        $nav_id = I('nav_id');
        $Nav = D('Nav');
        $nav = $Nav->get($nav_id);

        if ($nav) {
            $res['res'] = 1;
            $res['msg'] = $nav;
        } else {
            $res['res'] = -1;
            $res['msg'] = $nav;
        }
        echo json_encode($res);

    }

    public function del()
    {
        $Nav = D('Nav');
        $nav_id = I('nav_id');

        $where = [];
        $where['nav_id'] = $nav_id;
        
        //删除专题关联数据
        //删除商品关联数据
        $NavGoods = D('NavGoods');
        $NavSpecial = D('NavSpecial');

        $NavGoods->where($where)->delete();
        $result = $Nav->where($where)->delete();

        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);

    }

    public function save()
    {
        $Nav = D('Nav');
        $where = I('where');
        $save = I('save');
        $result = $Nav->where($where)->save($save);
        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);
    }

    public function saveData()
    {
        $Nav = D('Nav');
        $where = [];
        $where['nav_id'] = I('nav_id');
        $data = I('data');
        unset($data['add_time']);
        $data['edit_time'] = time();
        $result = $Nav->where($where)->save($data);
        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);
    }

    public function addGoods()
    {
        $NavGoods = D('NavGoods');
        $goodsIds = I('goodsIds');
        $nav_id = I('nav_id');
        $add = [];

        for ($i = 0; $i < count($goodsIds); $i++) {
            $goods_id = $goodsIds[$i];
            $nav_goods_id = getMd5('nav_goods_id');
            //如果存在就不添加
            $item = [];
            $item['nav_id'] = $nav_id;
            $item['goods_id'] = $goods_id;
            $isAdd = $NavGoods->where($item)->find();

            if (!$isAdd) {
                //不存在
                $item['nav_goods_id'] = $nav_goods_id;
                $item['add_time'] = time();
                $item['edit_time'] = time();
                $add[] = $item;
            }

        }

        if (count($add) > 0) {
            $result = $NavGoods->addAll($add);
        } else {
            $result = 1;
        }
        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);

    }
    public function addSpecial()
    {

        $specialIds = I('specialIds');
        $nav_id = I('nav_id');
        $NavSpecial = D('NavSpecial');
        $add = [];
        for ($i = 0; $i < count($specialIds); $i++) {
            $special_id = $specialIds[$i];
            $nav_special_id = getMd5('nav_special');
            //如果存在就不添加
            $item = [];
            $item['nav_id'] = $nav_id;
            $item['special_id'] = $special_id;
            $isAdd = $NavSpecial->where($item)->find();
            $item['nav_goods_id'] = $nav_goods_id;
            if (!$isAdd) {
                //不存在
                $item['nav_special_id'] = $nav_special_id;
                $item['add_time'] = time();
                $item['edit_time'] = time();
                $add[] = $item;
            }

        }
        if (count($add) > 0) {
            $result = $NavSpecial->addAll($add);
        } else {
            $result = 1;
        }
        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);
    }
    
    //删除商品
    public function delGoods()
    {

        $NavGoods = D('NavGoods');
        $goods_id = I('goods_id');
        $nav_id = I('nav_id');

        $where = [];
        $where['goods_id'] = $goods_id;
        $where['nav_id'] = $nav_id;
        $result = $NavGoods->where($where)->delete();

        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);

    }
    
    //删除专题
    public function delSpecial()
    {

        $NavSpecial = D('NavSpecial');
        $special_id = I('special_id');
        $nav_id = I('nav_id');

        $where = [];
        $where['special_id'] = $special_id;
        $where['nav_id'] = $nav_id;
        $result = $NavSpecial->where($where)->delete();

        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);

    }



}