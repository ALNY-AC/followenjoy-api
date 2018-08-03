<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月6日16:12:36
* 最新修改时间：2018年7月6日16:12:36
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分享会员控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class ShareMemberController extends Controller{
    
    public function create(){
        if(!I('user_id')){
            die;
        }
        session('user_id',I('user_id'));
        $ShareMember=D('ShareMember');
        $share_member_id=$ShareMember->create();
        if($share_member_id){
            $url=$ShareMember->getUrl($share_member_id);
            $res['res']=1;
            $res['msg']=$url;
            $res['share_member_id']=$share_member_id;
        }else{
            $res['res']=-1;
            $res['msg']='';
        }
        echo json_encode($res);
    }
    
    public function qrcode(){
        $user_id=I('user_id');
        $share_member_id=I('share_member_id');
        if(!$user_id || !$share_member_id){
            echo '参数错误';
            return;
        }
        // die;
        // ===================================================================================
        // // 满,减,数量,有效时长,生效时间,分区
        // $Coupon=D('Coupon');
        // $coupon=[];
        // $couponArr=$Coupon->获得满减券(0,100,100,50,0,'','测试-通用券');
        // $coupon=array_merge($coupon,$couponArr);
        
        // foreach ($coupon as $k => $v) {
        //     $v['user_id']='17521262891';
        //     $v['add_time']=time();
        //     $v['edit_time']=time();
        //     $coupon[$k]=$v;
        // }
        
        
        // $where=[];
        // $where['user_id']='17521262891';
        // $Coupon->where($where)->delete();
        
        // $Coupon->addAll($coupon);
        
        // die;
        // dump(I());
        // ===================================================================================
        // 图片生成
        
        $width=750;
        $height=1334;
        $padding=50;
        
        // 字体文件
        $font_file = "./Public/ttf/PingFang Regular.ttf";
        $font_file2 = "./Public/ttf/PingFang Medium.ttf";
        $font_file_bold = "./Public/ttf/PingFang Heavy.ttf";
        
        //创建画布
        $im = imagecreatetruecolor($width, $height);
        
        //填充画布背景色
        $color = imagecolorallocate($im, 35, 35, 35);
        imagefill($im, 0, 0, $color);
        
        // ===================================================================================
        // 背景图片
        
        list($g_w,$g_h) = getimagesize('./Public/Assets/ShareMember/images/bg.jpg');
        $bgImg = createImageFromFile('./Public/Assets/ShareMember/images/bg.jpg');
        imagecopyresized($im, $bgImg, 0, 0, 0, 0,  $width,  $height, $g_w, $g_h);
        
        // ===================================================================================
        // 用户头像参数
        
        $user_head_start_left=170;
        $user_head_start_top=210;
        
        $user_head_width=80;
        $user_head_left=$user_head_start_left;
        $user_head_top=$user_head_start_top;
        $user_head_bottom=$user_head_top+$user_head_width;
        $user_head_right=$user_head_left+$user_head_width;
        
        // ===================================================================================
        // 用户头像底色
        $dev=1.5;
        $fang_bg_color = ImageColorAllocate ($im, 186, 163, 113);
        imagefilledrectangle ($im, $user_head_left-$dev , $user_head_top-$dev , $user_head_right+$dev, $user_head_bottom+$dev, $fang_bg_color);
        
        
        // ===================================================================================
        // 用户头像
        
        $where=[];
        $where['user_id']=$user_id;
        $User=D('User');
        $user=$User
        ->field('user_head,user_name')
        ->where($where)
        ->find();
        // dump($user);
        // die;
        // $user=[];
        // $user['user_head']='https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1531289210795&di=ab234cfad6c6d3f78360e28852cc02b3&imgtype=0&src=http%3A%2F%2Fimg3.duitang.com%2Fuploads%2Fitem%2F201512%2F08%2F20151208153046_xcPHk.thumb.700_0.png';
        // $user['user_name']='user_name';
        list($g_w,$g_h) = getimagesize($user['user_head']);
        $userHeadImg = createImageFromFile($user['user_head']);
        imagecopyresized($im, $userHeadImg, $user_head_start_left, $user_head_start_top , 0, 0,  80,  80, $g_w, $g_h);
        
        // ===================================================================================
        // 描述文字
        $user_name=$user['user_name'];
        $str="HI，我是随享季会员$user_name";
        imagettftext($im ,16 , 0 , $user_head_left , $user_head_bottom+30 ,$fang_bg_color , $font_file2 , $str );
        
        
        // ===================================================================================
        // 二维码
        $host='http://'.$_SERVER['SERVER_NAME'].'/';
        
        $value=I('href');
        $value= urlencode($value);
        $codeUrl=$host.'home/ShareMember/getCode?value='.$value;
        
        
        $codeName=$codeUrl;
        list($code_w,$code_h) = getimagesize($codeName);
        $codeImg = createImageFromFile($codeName);
        $codeWidth=125;
        $codeHeight=125;
        $codeY=$height-125-40-30;
        $codeX=40;
        imagecopyresized($im, $codeImg, $codeX, $codeY, 0, 0, $codeWidth, $codeHeight, $code_w, $code_h);
        
        // ===================================================================================
        // 输出
        
        //输出图片
        if(I('down')){
            $fileName=$share_member_id.'.png';
            $fileName='Public/Assets/ShareMember/'.$fileName;
            Header("Content-Type: image/png");
            imagepng ($im,$fileName);
            download($fileName);
        }else{
            Header("Content-Type: image/png");
            imagepng ($im);
        }
        
        //释放空间
        imagedestroy($im);
        imagedestroy($bgImg);
        imagedestroy($codeImg);
        
        
    }
    
    
    public function getCode(){
        
        $value=I('value','',false);
        
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        $level='L';//容错级别
        $size=5;//大小
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        // 'https://mmbiz.qlogo.cn/mmbiz_png/EISicquI57gst6WUxm3ksJgTtNBSyzL1ja1UHDQNDVcTySfib4BfYRnbFXnE3Bpx1wKJ08QjnAR6scaNoicBJn5uw/0?wx_fmt=png'
        $object->png($value,false, $errorCorrectionLevel, $matrixPointSize, 3);
        
        //需要显示在二维码中的Logo图像
        // $icon = 'https://mmbiz.qlogo.cn/mmbiz_png/EISicquI57gst6WUxm3ksJgTtNBSyzL1ja1UHDQNDVcTySfib4BfYRnbFXnE3Bpx1wKJ08QjnAR6scaNoicBJn5uw/0?wx_fmt=png';
        
        // if($icon){
        //     $code = ob_get_clean();
        //     $code = imagecreatefromstring($code);
        //     $logo = imagecreatefrompng($icon);
        //     $QR_width = imagesx($code);//二维码图片宽度
        //     $QR_height = imagesy($code);//二维码图片高度
        //     $logo_width = imagesx($logo);//logo图片宽度
        //     $logo_height = imagesy($logo);//logo图片高度
        //     $logo_qr_width = $QR_width / 4;
        //     $scale = $logo_width/$logo_qr_width;
        //     $logo_qr_height = $logo_height/$scale;
        //     $from_width = ($QR_width - $logo_qr_width) / 2;
        //     //重新组合图片并调整大小
        //     imagecopyresampled($code, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        //     header ( "Content-type: image/png" );
        //     ImagePng($code);die;
        // }
        
    }
    
    public function show(){
        
        $ShareMember=D('ShareMember');
        
        $share_member_id=I('share_member_id');
        // ===================================================================================
        // 判断是否过期
        // http://192.168.1.251:8080
        // $is=$ShareMember->isExpire($share_member_id);
        if(true){
            // 未过期
            // ===================================================================================
            // 取出分享人信息
            $share_id=I('share_id');
            $shop_id=I('shop_id');
            
            $href="http://q.followenjoy.cn/#/goodsInfo?goods_id=396&shop_id=$shop_id&share_id=$share_id";
            // $href="http://192.168.1.251:8080/#/goodsInfo?goods_id=34&shop_id=$shop_id&share_id=$share_id";
            
            $this->assign('href',$href);
            $this->display();
        }
        
    }
    
}