<?php

/**
 * +----------------------------------------------------------------------
 * 创建日期：2018年6月22日10:44:55
 * 最新修改时间：2018年6月22日10:44:55
 * +----------------------------------------------------------------------
 * https：//github.com/ALNY-AC
 * +----------------------------------------------------------------------
 * 微信：AJS0314
 * +----------------------------------------------------------------------
 * QQ:1173197065
 * +----------------------------------------------------------------------
 * #####分享控制器#####
 * @author 代码狮
 *
 */
namespace Home\Controller;

use Think\Controller;

class ShareController extends Controller
{

    public function test()
    {


        $title = "分享的标题";
        $price = "39.9";
        
        
        //使用方法-------------------------------------------------
        //数据格式，如没有优惠券coupon_price值为0。
        $gData = [
            'pic' => 'http://server.followenjoy.cn/Public/Upload/admin2018-05-27/5b0a885428b14.jpg',
            'title' => '1件装 | MELLOW Z1无线多媒体边桌',
            'sub_title' => '随时随地 乐享生活',
            'price' => 117.41,
            'user_head' => 'http://server.followenjoy.cn/Public/Upload/admin/2018-05-10/5af3ded33994d.jpeg',
            'user_name' => '卖牛肉的老先生',
            'is_time' => true,
        ];
        
        
        //直接输出
        createSharePng($gData, 'http://cosmetics.cn/home/Share/test2');
        //输出到图片
        // createSharePng($gData,'code_png/php_code.jpg','share.png');

    }
    
    // public function getGoodsImageUrl(){
    
    //     $goods_id=I('goods_id');
    //     $user_id=I('user_id');
    
    //     $p=[];
    //     $p['goods_id']=$goods_id;
    //     $p['user_id']=$user_id;
    //     $url=U('Share/getGoodsImage',$p,'',true);
    
    //     $res=[];
    //     $res['msg']=$url;
    
    //     echo json_encode($res);
    
    // }

    /**
     * curl 提交数据
     *
     * @param string $url
     * @param string $body
     * @param string $method
     * @return type
     */
    private function curl($url, $header, $body, $method)
    {
        $header['Content-Length'] = strlen($body);
        foreach ($header as $key => $val) {
            $header[$key] = $key . ": " . $val;
        }
        $header = array_values($header);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->unparseUrl($url));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getGoodsImage()
    {

        $goods_id = I('goods_id');
        $user_id = I('user_id');
        
        // ===================================================================================
        // 创建模型
        $Goods = D('Goods');
        $GoodsImg = D('GoodsImg');
        $Sku = D('Sku');
        $User = D('User');

        $host = 'http://' . $_SERVER['SERVER_NAME'] . '/';
        // $host='http://server.followenjoy.cn/';
        $where = [];
        $where['goods_id'] = $goods_id;
        // ===================================================================================
        // 取出商品信息
        $limit = [];
        $limit['img_list'] = 1;
        $limit['sku'] = 1;
        $goods = $Goods->get($goods_id, ['img_list', 'sku'], '*', $limit);

        $goodsImg = $goods['img_list'][0]['src'];
        // http://server.followenjoy.cn//Public/Upload/admin2018-08-01/5b61654870423.jpg

        // ec($goodsImg);
        // die;
        // ===================================================================================
        // 取出sku信息
        $sku = $goods['sku'][0];
        
        
        // ===================================================================================
        // 取出用户信息
        $where = [];
        $where['user_id'] = $user_id;
        $user = $User->where($where)->find();

        $gData = [];
        $gData['pic'] = $host . $goodsImg;
        $gData['title'] = $goods['goods_title'] . '';
        $gData['sub_title'] = $goods['sub_title'] . '';
        // $gData['title']='Lorem ipsum';
        // $gData['sub_title']='Lorem ipsum consectetur adipisicing elit.';
        // Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur consequuntur sunt vero ducimus eius esse, mollitia, at praesentium fugit molestias atque placeat explicabo veritatis temporibus dicta recusandae vitae magni quibusdam!

        $gData['price'] = $sku['price'];
        $gData['user_head'] = $host . $user['user_head'];
        // $gData['user_name']=$user['user_name'];
        $gData['user_name'] = $user['user_name'];

        if ($goods['is_time']) {
            $gData['origin'] = [];
            $gData['origin']['time'] = date('m月d日 H:i', $goods['activity_time']);
        }

        $value = I('href', '', false);
        $value = urlencode($value);
        $codeUrl = $host . 'home/Share/getCode?value=' . $value;


        if (I('down')) {
            createSharePng($gData, $codeUrl, $goods['goods_id'] . '.png');
        } else {
            createSharePng($gData, $codeUrl);
        }

    }


    public function getCode()
    {

        $value = I('value', '', false);

        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        $level = 'L';//容错级别
        $size = 5;//大小
        $errorCorrectionLevel = intval($level);//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        // 'https://mmbiz.qlogo.cn/mmbiz_png/EISicquI57gst6WUxm3ksJgTtNBSyzL1ja1UHDQNDVcTySfib4BfYRnbFXnE3Bpx1wKJ08QjnAR6scaNoicBJn5uw/0?wx_fmt=png'
        $object->png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);
        
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


}