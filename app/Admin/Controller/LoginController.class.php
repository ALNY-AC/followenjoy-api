<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月27日22:20:38
* 最新修改时间：2018年6月27日22:20:38
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####登录控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    
    //构造函数
    public function _initialize(){
        
    }
    
    //主
    public function index(){
        
        
    }
    
    public function codeLogin(){
        $qrcode_id=I('qrcode_id');
        
        $Qrcode=D('Qrcode');
        $where=[];
        $where['qrcode_id']=$qrcode_id;
        $result=$Qrcode->where($where)->find();
        
        
        $Admin=D('Admin');
        $where['unionid']=$result['unionid'];
        $admin=$Admin->where($where)->find();
        
        
        if($admin){
            // 存在
            $token=createToken($admin['admin_id']);
            
        }else{
            // 不存在
            $token='';
        }
        
        if($token){
            $res['res']=1;
            $res['token']=$token;
            $res['admin_id']=$admin['admin_id'];
            
            $Qrcode=D('Qrcode');
            $where=[];
            $where['qrcode_id']=$qrcode_id;
            $result=$Qrcode->where($where)->delete();
            
            
        }else{
            $res['res']=-1;
            $res['token']=$token;
            $res['admin_id']=$admin['admin_id'];
        }
        
        echo json_encode($res);
        
    }
    
    public function loginPage(){
        
        
        // ===================================================================================
        // 微信登录
        /***
        http://cosmetics.cn/index.php/Home/ShareRed/init/share_red_id/78139d86c1b2ba8782c77bd54fcabb11
        */
        
        if(session('code')==I('code') || !I('code')){
            // 重新请求
            $qrcode_id=I('qrcode_id');
            $APPID='wx56a5a0b6368f00a7';
            
            $p=[];
            $p['qrcode_id']=$qrcode_id;
            $redirect_uri=U('Login/loginPage',$p,'',true);
            $redirect_uri= urlencode($redirect_uri);
            
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$APPID&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            
            echo "<script>window.location.href='$url'</script>";
            die;
        }else{
            session('code',I('code'));
        }
        
        $qrcode_id=I('qrcode_id');
        // echo "<h1>$share_red_id</h1>";
        $this->assign('qrcode_id',$qrcode_id);
        
        if(true){
            
            $APPID='wx56a5a0b6368f00a7';
            $secret='643f69abc138477f4362ab22a5d012c0';
            $code=I('code');
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$secret&code=$code&grant_type=authorization_code";
            // ===================================================================================
            // 取令牌
            $accessData=_request($url);
            $accessData=json_decode($accessData,true);
            $access_token=$accessData['access_token'];
            $openid=$accessData['openid'];
            
            // ===================================================================================
            // 取用户信息
            $url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
            $userInfo=_request($url);
            $userInfo=json_decode($userInfo,true);
            $unionid=$userInfo['unionid'];
            $nickname=$userInfo['nickname'];
            $headimgurl=$userInfo['headimgurl'];
            
        }
        
        session('unionid',$unionid);
        session('nickname',$nickname);
        session('headimgurl',$headimgurl);
        $weixinInfo=[];
        $weixinInfo['unionid']=$unionid;
        $weixinInfo['nickname']=$nickname;
        $weixinInfo['headimgurl']=$headimgurl;
        
        // $weixinInfo['unionid']='oaCju0o2cXHvURZwqD3mwAezSC_U';
        // $weixinInfo['nickname']='朕的江山';
        // $weixinInfo['headimgurl']='http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIiafLFaeRqCecPFJRYfQ5wpfewZWHh7I7iablqg2mzCq80SQibvvhXAoOo7QIRrX0Y1uHDfMLlykBTw/132';
        
        
        $this->assign('weixinInfo',json_encode($weixinInfo));
        
        
        // ===================================================================================
        // 检测是否第一次登录
        
        $Admin=D('Admin');
        $where=[];
        $where['unionid']=$unionid;
        $admin=$Admin->where($where)->field('admin_id,admin_name,unionid')->find();
        if($admin){
            // 已有数据
            $token=createToken($admin['admin_id']);
            $isBinding=1;
            
        }else{
            // 没有数据
            // 需要绑定
            $token='';
            $isBinding=0;
        }
        $this->assign('token',$token);
        
        $this->assign('adminInfo',json_encode($admin));
        
        $this->assign('isBinding',$isBinding);
        
        $this->display('loginPage');
        
    }
    public function setPhone(){
        $admin_id=I('admin_id');
        $unionid=I('unionid');
        $admin_pwd=I('admin_pwd');
        $qrcode_id=I('qrcode_id');
        
        $Admin=D('Admin');
        
        $result= login('admin',$admin_id,$admin_pwd);
        
        if($result){
            
            
            // 绑定
            $Admin=D('Admin');
            $data=[];
            $data['unionid']=$unionid;
            $where=[];
            $where['admin_id']=$admin_id;
            
            $Admin->where($where)->save($data);
            
            $res['res']=1;
        }else{
            $res['res']=-1;
        }
        echo json_encode($res);
    }
    
    public function setLoginState(){
        
        $admin_id=I('admin_id');
        $unionid=I('unionid');
        $admin_pwd=I('admin_pwd');
        $qrcode_id=I('qrcode_id');
        
        $Qrcode=D('Qrcode');
        $data=[];
        $data['qrcode_id']=$qrcode_id;
        $data['unionid']=$unionid;
        $result=$Qrcode->add($data,'',true);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
    //空操作
    public function qrcode(){
        
        $qrcode_id=I('qrcode_id');
        
        $p=[];
        $p['qrcode_id']=$qrcode_id;
        $url=U('Login/loginPage',$p,'',true);
        
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        $level=10;//容错级别
        $size=5;//大小
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    }
    
    public function getQrcode(){
        $qrcode_id=md5(rand());
        $res['msg']=$qrcode_id;
        echo json_encode($res);
    }
    
}