<?php
/**
 * Created by PhpStorm.
 * User: Define
 * Date: 2018/5/31
 * Time: 15:50
 */

namespace App\Http\Service;
use PHPUnit\Runner\Exception;

include_once '../app/Libs/Qrcode/phpqrcode.php';

class QrCodeService{
    private static function qrCodePath(){
        $dir = 'Qrcode/'.date("Y-m-d",time()).'/';
        if(!is_dir($dir)){
            mkdir($dir,0777,true);//自动建文件  1文件名、2权限、3递归
        }
        $file = uniqid().'.png';
        return $dir.$file;
    }

    public static function png($instrument)
    {
        $value = $instrument; //二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片
        //生成二维码地址
        $path = self::qrCodePath();
        if(!$path){
            throw new Exception('网络异常',4005);
        }
        \QRcode::png($value,$path, $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = 'logo.png';//准备好的logo图片
        $QR = $path;//已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        imagepng($QR, $path);
        return $path;

    }
}