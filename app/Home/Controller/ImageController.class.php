<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月9日15:25:22
* 最新修改时间：2018年7月9日15:25:22
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####图片控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class ImageController extends Controller{
    
    public function get(){
        $width=I('width')?I('width'):32;
        $height=I('height')?I('height'):32;
        header('Content-Type: image/jpg');
        $src=I('src');
        $image = new \Think\Image();
        $image->open($src);
        $image->thumb($width, $height)->save(null);
    }
    
}