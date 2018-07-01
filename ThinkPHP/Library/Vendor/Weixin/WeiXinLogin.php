<?php
/**
*
* 微信
*
*/
class WeiXinLogin {
    
    private $redirect_uri='';
    private $appid='wx56a5a0b6368f00a7';
    private $secret='643f69abc138477f4362ab22a5d012c0';
    private $openid='';
    private $userInfo=null;
    
    /**
    * 设置微信登录重定向的url
    */
    public function setRedirectUrl($redirect_uri){
        $this->redirect_uri=$redirect_uri;
    }
    
    /**
    * 获取微信登录重定向的url
    */
    public function getRedirectUrl(){
        return  $this->redirect_uri;
    }
    
    /**
    * 设置appid
    */
    public function setAppid($appid){
        $this->appid=$appid;
    }
    
    /**
    * 取得appid
    */
    public function getAppid(){
        return $this->appid;
    }
    
    /**
    * 设置微信令牌
    */
    public function setSecret($secret){
        $this->secret=$secret;
    }
    
    /**
    * 取得微信令牌
    */
    public function getSecret(){
        return $this->secret;
    }
    
    public function setOpenid($openid){
        $this->openid=$openid;
    }
    
    public function getOpenid(){
        return  $this->openid;
    }
    
    
    /**
    * 微信公众号登录操作，可以传入参数设置是否跳转
    */
    public function login($jump=true,$replace=false){
        
        $shop_id=I('shop_id');
        $appid=$this->getAppid();
        $redirect_uri=$this->getRedirectUrl();
        $redirect_uri=urlencode($redirect_uri);
        
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        
        if($jump){
            echo "<script>window.location.href='$url'</script>";
            $this->jump($url,$replace);
        }else{
            return $url;
        }
        
    }
    
    /**
    * 跳转到指定连接
    */
    public function jump($url,$replace=false){
        if($replace){
            echo "<script>window.location.replace=('$url')</script>";
        }else{
            echo "<script>window.location.href='$url'</script>";
        }
    }
    
    
    /**
    * 先初始化一下登录信息
    */
    public function initLoginInfo(){
        
        // ===================================================================================
        // 设置基本参数
        
        $data=I();
        $appid=$this->getAppid();
        $secret=$this->getSecret();
        
        
        // ===================================================================================
        // 取得access_token和openid
        $code=I('code');
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $accessData=$this->_request($url);
        $accessData=json_decode($accessData,true);
        $access_token=$accessData['access_token'];
        $openid=$accessData['openid'];
        $this->setOpenid($openid);
        
        // ===================================================================================
        // 取得用户信息
        $url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userInfo=$this->_request($url);
        $userInfo=json_decode($userInfo,true);
        $this->setUserInfo($userInfo);
        
    }
    
    /**
    * 设置用户信息
    */
    public function setUserInfo($userInfo){
        $this->userInfo=$userInfo;
    }
    
    /**
    * 取得用户信息
    */
    public function getUserInfo(){
        return $this->userInfo;
    }
    
    public function _request(){
        // 创建一个新cURL资源
        $ch = curl_init();
        
        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $curl);    //要访问的网站
        curl_setopt($ch, CURLOPT_HEADER, false);    //启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //将curl_exec()获取的信息以字符串返回，而不是直接输出。
        
        if($https){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //FALSE 禁止 cURL 验证对等证书（peer's certificate）。
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  //验证主机
        }
        if($method == 'POST'){
            curl_setopt($ch, CURLOPT_POST, true);  //发送 POST 请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //全部数据使用HTTP协议中的 "POST" 操作来发送。
        }
        
        // 抓取URL并把它传递给浏览器
        $content = curl_exec($ch);
        if ($content  === false) {
            return "网络请求出错: " . curl_error($ch);
            exit();
        }
        //关闭cURL资源，并且释放系统资源
        curl_close($ch);
        return $content;
    }
    
    /**
    * 格式化参数格式化成url参数
    */
    public function toUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }
    
}