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
    const STAR_URL = '/followenjoy/';
//    /Home/ShortLink/shortLink
//长链生成短链
    public function shortLink(){
        
        $long_url = I('long_url','',false);
        
        $res['res'] = 1;
        $res['msg'] =$long_url;
        echo json_encode($res);
        exit;
        if(!$long_url){
            $res['res'] = -1;
            echo json_encode($res);
            exit;
        }
        //        $long_url='http://q.followenjoy.cn/#/goodsInfo?&goods_id=1545&shop_id=30997992&share_id=18221196274';
        $where['long_url'] = $long_url;
        $url = D('short_link')->where($where)->getField('sort_url');
        if($url){
            $res['res'] = 1;
            $res['msg'] = C('sort_url.SORT_URL').$url;
            echo json_encode($res);
            exit;
        }
        $sort_url = uniqid();
        $add['add_time'] = time();
        $add['edit_time'] = time();
        $add['key'] = md5($long_url);
        $add['long_url'] = $long_url;
        $add['sort_url'] = self::STAR_URL.$sort_url;
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
        $a = '/aaaa/';

        $count = strpos($a,"/");  //strpos(string,find,start)返回所筛选的字符串出现的位置 参数start可选

        $a = substr_replace($a,"",$count,2);//substr_replace(string,replacement,start,length)参数length可选

echo $a;//输出acdfigcd
die;
        $long_url = I('SXJ','',false);
        $where['sort_url'] = self::STAR_URL.$long_url;
        $res = D('short_link')->where($where)->getField('long_url');
        if(!$res){

        }
        header("Location: $res");
    }
}