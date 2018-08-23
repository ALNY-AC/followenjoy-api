<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年8月23日16:18:58
* 最新修改时间：2018年8月23日16:18:58
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####二维码生成器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class QrcodeController extends Controller{
    
    public function getCode(){
        
        $value=I('value','',false);
        
        Vendor('phpqrcode.phpqrcode');
        // Vendor('qrcode.phpqrcode');
        //生成二维码图片
        $level='L';//容错级别
        $size=6;//大小
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        // 'https://mmbiz.qlogo.cn/mmbiz_png/EISicquI57gst6WUxm3ksJgTtNBSyzL1ja1UHDQNDVcTySfib4BfYRnbFXnE3Bpx1wKJ08QjnAR6scaNoicBJn5uw/0?wx_fmt=png'
        \QRcode::png($value,false, $errorCorrectionLevel, $matrixPointSize, 2);

        //需要显示在二维码中的Logo图像
        return;
        $icon = 'https://mmbiz.qlogo.cn/mmbiz_png/EISicquI57gst6WUxm3ksJgTtNBSyzL1ja1UHDQNDVcTySfib4BfYRnbFXnE3Bpx1wKJ08QjnAR6scaNoicBJn5uw/0?wx_fmt=png';
        
        if($icon){
            $code = ob_get_clean();
            $code = imagecreatefromstring($code);
            $logo = imagecreatefrompng($icon);
            $QR_width = imagesx($code);//二维码图片宽度
            $QR_height = imagesy($code);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($code, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            // header ( "Content-type: image/png" );
            ImagePng($code);
        }
        
    }
    
    
    
}