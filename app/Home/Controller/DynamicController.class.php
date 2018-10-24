<?php

/**
 * +----------------------------------------------------------------------
 * 创建日期：2018年3月5日10:56:44
 * 最新修改时间：2018年3月5日10:56:44
 * +----------------------------------------------------------------------
 * https：//github.com/ALNY-AC
 * +----------------------------------------------------------------------
 * 微信：AJS0314
 * +----------------------------------------------------------------------
 * QQ:1173197065
 * +----------------------------------------------------------------------
 * #####动态控制器#####
 * @author 代码狮
 *
 */
namespace Home\Controller;

use Think\Controller;

class DynamicController extends Controller
{

    public function test()
    {
        // $save=[];
        // $save['release_time']=time();//当前时间到达发布时间
        // M('dynamic')->where('1=1')->save($save);
    }
    
    //添加
    public function add()
    {
        //要添加的数据
        $add = [];
        //获得发来的要添加的数据
        $add = I('add', '', false);
        if (!$add) {
            $res['res'] = -2;
            echo json_encode($res);
            die;
        }
        //模型
        $model = M('dynamic');
        
        //添加img
        $imgList = $add['img_list'];

        $dynamic_id = getMd5('dynamic');

        $img_list = [];

        for ($i = 0; $i < count($imgList); $i++) {

            $add['dynamic_img_id'] = getMd5('dynamic_img');
            $add['dynamic_id'] = $dynamic_id;
            $add['src'] = $imgList[$i];
            $add['add_time'] = time();
            $add['edit_time'] = time();
            $img_list[] = $add;
        }

        $DynamicImg = M('dynamic_img');
        $DynamicImg->addAll($img_list);

        unset($add['img_list']);
        
        //设置用户id
        $add['user_id'] = session('user_id');
        $add['add_time'] = time();
        $add['edit_time'] = time();
        $add['dynamic_id'] = $dynamic_id;
        
        //添加进去
        $result = $model->add($add);
        // 判断
        if ($result) {
            $res['res'] = 1;
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);
    }
    
    // public function getUpList(){
    
    //     $Dynamic=D('Dynamic');
    //     $dynamic=$Dynamic->getList(I());
    //     $res['res']=1;
    //     $res['msg']=$dynamic;
    //     echo json_encode($res);
    
    // }

    public function getUpList()
    {
        $User = D('User');
        $upUsers = $User->getUpList();

        if ($upUsers) {
            $res['res'] = count($upUsers);
            $res['msg'] = $upUsers;
        } else {
            $res['res'] = -1;
            $res['msg'] = $upUsers;
        }
        echo json_encode($res);
    }
    
    
    //获得列表
    public function getList()
    {

        $Dynamic = D('Dynamic');

        $data = I();

        $result = $Dynamic->getList($data);
        $res['count'] = $Dynamic
            ->where($data['where'] ? $data['where'] : [])
            ->count() + 0;

        if ($result !== false) {
            $res['res'] = count($result);
            $res['msg'] = $result;
        } else {
            $res['res'] = -1;
            $res['msg'] = $result;
        }
        echo json_encode($res);
    }

    public function getUpUser()
    {
        $res = [];
        $User = D('User');
        $upUsers = $User->getUpList();
        $res['msg'] = $upUsers;
        $res['res'] = 1;
        echo json_encode($res);
    }




}