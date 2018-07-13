<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月21日11:55:39
* 最新修改时间：2018年6月21日11:55:39
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分享红包控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class ShareRedController extends Controller{
    
    public function create(){
        $ShareRed=D('ShareRed');
        $result=$ShareRed->create(I('data'));
        if($result){
            $res['res']=1;
            $res['url']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function pull(){
        
        $ShareRed=D('ShareRed');
        $result=$ShareRed->pull(I());
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function login(){
        
        $share_red_id=I('share_red_id');
        // echo "<h1>$share_red_id</h1>";
        
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
        
        session('unionid',$unionid);
        session('nickname',$nickname);
        session('headimgurl',$headimgurl);
        
        $p=[];
        $p['share_red_id']=$share_red_id;
        $url=U('ShareRed/show',$p,'',true);
            
        echo "<script>window.location.replace('$url')</script>";
        
    }
    
    public function show(){
        
        //http://server.followenjoy.cn/index.php/Home/ShareRed/show/share_red_id/8f81db82befee32798e4b1fcc25376a6
        $share_red_id=I('share_red_id');
        // 如果unionid不存在，需要登录
        
        // if(!session('unionid')){
        //     // 不存在需要登录
        //     $p=[];
        //     $p['share_red_id']=$share_red_id;
        //     $url=U('ShareRed/init',$p,'',true);
        //     echo "<script>window.location.href='$url'</script>";
        //     exit;
        // }
        
        /***
        *
        http://cosmetics.cn/index.php/Home/ShareRed/show/share_red_id/78139d86c1b2ba8782c77bd54fcabb11
        *
        */
        
        // ===================================================================================
        // 配置一些参数
        
        $ShareRedRecord=D('ShareRedRecord');//红包领取记录模型
        
        // ===================================================================================
        // 取出所有记录
        $where=[];
        $where['share_red_id']=$share_red_id;
        $recordList=$ShareRedRecord->where($where)->select();
        $recordList=toTime($recordList,'');
        
        // ===================================================================================
        // 取出自己领取的
        
        $where=[];
        $where['unionid']=session('unionid');
        $where['share_red_id']=$share_red_id;
        $myRecord=$ShareRedRecord->where($where)->find();
        
        // 组装信息
        $info=[];
        if($myRecord['price']==5){
            // 最大
            $info[]='满30可用';
        }
        
        $end_time=strtotime("+2 day",$myRecord['add_time']);//后台计算24小时后的时间戳，用户限制，一般只取当天的时间
        $info[]=date('m-d',$end_time).' 到期';
        
        $myRecord['info']=$info;
        
        $this->assign('myRecord',json_encode($myRecord));
        
        // ===================================================================================
        // 最大的人
        $is_max=false;
        foreach ($recordList as $k => $v) {
            if($v['price']==5){
                $v['is_up']=true;
                
                if(!$is_max){
                    if($v['unionid']==session('unionid')){
                        $is_max=true;
                    }
                }
                
            }else{
                $v['is_up']=false;
            }
            $recordList[$k]=$v;
        }
        
        // ===================================================================================
        // 自己是否是最大的
        $this->assign('is_max',$is_max);
        
        $this->assign('recordList',json_encode($recordList));
        $this->assign('share_red_id',$share_red_id);
        $this->display();
    }
    
    public function init(){
        // ===================================================================================
        // 微信登录
        /***
        http://cosmetics.cn/index.php/Home/ShareRed/init/share_red_id/78139d86c1b2ba8782c77bd54fcabb11
        */
        
        $share_red_id=I('share_red_id');
        $APPID='wx56a5a0b6368f00a7';
        // $host='http://cosmetics.cn';
        $host="http://server.followenjoy.cn/Home/ShareRed/Login";
        $redirect_uri="$host?share_red_id=$share_red_id";
        $redirect_uri= urlencode($redirect_uri);
        // dump($APPID);
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$APPID&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        // dump($url);
        echo "<script>window.location.href='$url'</script>";
        
    }
    
    
    // public function getMoney(){
    
    // $总人数=7;
    // $总钱数=10;
    // $最大可得钱=5;
    
    // $要计算的人数=$总人数-1;
    
    // $要计算的人数要分的钱=$总钱数-$最大可得钱;
    
    // $min=($要计算的人数要分的钱-$最大可得钱)/($要计算的人数-1);
    
    // $a=0;
    
    // $arg=$要计算的人数要分的钱/$要计算的人数;
    
    // $c=($arg-0.1)/($要计算的人数-1);//最后一个平均分给其他的值
    // dump('c：'.$c);
    // $arr=[];
    // for ($i=1; $i <= $要计算的人数; $i++) {
    
    // if($i==$要计算的人数){
    // $b= 0.1+$a;
    // $b=sprintf("%.2f",substr(sprintf("%.2f", $b*100), 0, -2))/100;
    // $arr[]=$b;
    // break ;
    // }
    
    // $b=randomFloat($min,$arg+$a+$c);
    // $b=sprintf("%.2f",substr(sprintf("%.2f", $b*100), 0, -2))/100;
    
    // $arr[]=$b;
    // dump('所得钱：'.$b);
    // $a=$arg+$a+$c-$b;
    
    // echo "b：$b\t\t\ta：$a </br>";
    
    // }
    // $sub=0;
    // foreach ($arr as $key => $value) {
    //     $sub+=$value;
    //     $百分比=($value/(10))*100;
    //     echo "所得：$value </p><p><div style='height:30px;display:inline-block;background-color:#f00;width:".($百分比)."%"."'>$百分比%</div></p>";
    // }
    // dump('人众所得钱：'.$sub);
    
    
    // }
    
    
    // public $remainSize=0;
    // public $remainMoney=0;
    
    
    // // echo'<hr/>';
    // $arr=[];
    // $size=5;
    // $price=10;
    // $this->remainSize=$size;
    // $this->remainMoney=$price;
    
    // public  function getRandomMoney() {
    //     // remainSize 剩余的红包数量
    //     // remainMoney 剩余的钱
    //     if ($this->remainSize == 1) {
    //         $this->remainSize--;
    //         $money=sprintf("%.2f",substr(sprintf("%.2f",  $this->remainMoney * 100), 0, -2))/100;
    //         // $this->remainMoney -= $money;
    //         return $money;
    //     }
    //     $min   = 0.01; //
    //     $max   = $this->remainMoney / $this->remainSize * 2;
    //     $money = randomFloat() * $max;
    
    //     // dump($money);
    
    //     $money = $money <= $min ? 0.01: $money;
    //     // $money = Math.floor(money * 100) / 100;
    //     $money=sprintf("%.2f",substr(sprintf("%.2f", $money*100), 0, -2))/100;
    //     $this->remainSize--;
    //     $this->remainMoney -= $money;
    //     return $money;
    // }
}