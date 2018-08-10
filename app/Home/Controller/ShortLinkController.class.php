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
    public function shortLink(){
        echo uniqid();
    }
//    /Home/ShortLink/test
    public function test(){
        echo 1111;
    }
}