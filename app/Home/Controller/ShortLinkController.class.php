<?php
/**
 * Created by PhpStorm.
 * User: User2
 * Date: 2018/8/10
 * Time: 10:48
 */
namespace Home\Controller;
use Think\Controller;

class ShortLinkController extends Controller{
//    /Home/ShortLink/shortLink
//长链生成短链
    public function shortLink(){
        $long_url = I('long_url','',false);
//        $long_url='http://q.followenjoy.cn/#/goodsInfo?&goods_id=1545&shop_id=30997992&share_id=18221196274';
        $sort_url = uniqid();
        $add['add_time'] = time();
        $add['edit_time'] = time();
        $add['key'] = md5($long_url);
        $add['long_url'] = $long_url;
        $add['sort_url'] = '/SXJ/'.$sort_url;
        $row = D('short_link')->add($add);
        if(!$row){
            $res['res'] = -1;
            exit;
        }
        $res['res'] = 1;
        $res['msg'] = C('sort_url.SORT_URL').$add['sort_url'];
        echo json_encode($res);
        exit;
    }
//    /Home/ShortLink/getUrl
//跳转原始地址
    public function getUrl(){
        $long_url = I('SXJ','',false);
        $where['sort_url'] = '/SXJ/'.$long_url;
        $res = D('short_link')->where($where)->getField('long_url');
        header("Location: $res");
    }
}