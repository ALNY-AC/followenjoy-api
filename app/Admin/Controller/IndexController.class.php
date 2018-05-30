<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    
    public function _initialize(){
        
        
    }
    
    public function index(){
        
        
    }
    
    public function update(){
        
        $Test=D('Test');
        $list=$Test
        ->group("user")
        ->select();
        dump($list);
        
    }
    
    
    public function test(){
        
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        $url='12138';//网址或者是文本内容
        $level=3;//容错级别
        $size=4;//大小
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
        
    }
    
    public function login(){
        
        //这里是管理的登录，所以必须判断管理权限
        
        //所有的用户都在user中，通过权限来区别管理和普通用户
        //取得登录数据
        $admin_code=I('post.admin_code',false);
        $admin_pwd=I('post.admin_pwd',false);
        $admin_id=I('post.admin_id',false);
        $res=[];
        
        //验证图片验证码是否正确
        if(isCode($admin_code)){
            
            //图片验证码正确
            //验证账户密码
            $result= login('admin',$admin_id,$admin_pwd);
            if($result){
                
                //是管理权限，验证成功，继续操作
                //账户和密码正确，创建token
                $token=createToken($admin_id);
                if($token){
                    //创建成功
                    $res['res']=1;
                    $res['token']=$token;
                }else{
                    //创建 token 失败
                    $res['res']=-3;
                }
                
            }else{
                //账户和密码不正确
                $res['res']=-1;
            }
            
        }else{
            //图片验证码不正确
            $res['res']=-2;
        }
        echo json_encode($res);
        
        
    }
    
    /**
    * 获得验证码
    */
    public function getCode(){
        getCode();
    }
    /**
    * 判断是否登录
    */
    public function islogin(){
        
        $is=isUserLogin('admin');
        $res['res']= $is;
        echo json_encode($res);
        
    }
    public function sinOut(){
        //获得传来的token
        $token=I('token');
        //获得传来的id
        $admin_id=I('admin_id');
        //创建token的控制器
        $model=M('token');
        //创建条件
        $where['login_id']=$admin_id;
        //删除token
        $result=$model->where($where)->delete();
        //清空session
        session(null);
        
        if($result){
            //退出成功
            $res['res']=1;
        }else{
            //退出失败
            $res['res']=-1;
        }
        
        echo json_encode($res);
    }
    
}