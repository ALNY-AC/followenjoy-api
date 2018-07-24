<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日15:23:103
* 最新修改时间：2018年4月8日15:23:103
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####主控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{
    
    public function info(){
        echo "<h1>Hello World,I'm Followenjoy Ctos</h1>";
        $time=time();
        $date=date('Y-m-d H:i:s',time());
        echo "<p>当前时间戳：$time</p>";
        echo "<p>当前时间：$date</p>";
        echo "<hr/>";
    }
    
    public function index(){
        $this->info();
        die;
        import('Org.Util.Origin.Order.Order');
        
        $pay = new \Pay();
        
        $order1 = new \Order();
        $order2 = new \Order();
        
        $goods1 = new \Goods();
        $goods2 = new \Goods();
        
        $order1->setGoods($goods1);
        $order2->setGoods($goods2);
        
        $goods1->setPrice(10);
        $goods1->setNum(1);
        $goods1->setTitle('商品1');
        $goods2->setPrice(20);
        $goods2->setNum(1);
        $goods2->setTitle('商品2');
        
        $time = new \Time();
        
        
        $goods1->addComponent($time);
        
        $pay->addOrder($order1);
        $pay->addOrder($order2);
        
        
        
        $coupon = new \Coupon();
        
        $pay->addComponent($coupon);
        
        $price=$pay->valuation();
        
        
        // ===================================================================================
        // 使用优惠券的场景
        
        
        dump($price);
        
        
        // ===================================================================================
        // 模拟订单流程
        
        
        
        // 1532047122
        
        // $where=[];
        // $where['unionid']=$unionid;
        // $User=D('User');
        // $user=$User->where($where)->find();
        // if(!$user){
        //     // 没有创建新用户
        //     $data=[];
        //     $data['user_id']=$unionid;
        //     $data['user_name']=$nickname;
        //     $data['user_head']=$headimgurl;
        //     $data['unionid']=$unionid;
        //     $data['user_vip_level']=0;
        //     $data['user_money']=0;
        //     $data['add_time']=time();
        //     $data['edit_time']=time();
        //     $User->add($data);
        //     $user=$User->where($where)->find();
        // }else{
        //     // 绑定 unionid
        //     $save=[];
        //     $save['unionid']=$unionid;
        //     $User->where($where)->save($save);
        // }
        
        
        // ===================================================================================
        // 检测用户id是否存在
        // 如果用户id不存在，就需要绑定，或者，如果用户id和unionid一样，也需要绑定（这一点是为了修复之前的问题）
        
        // $host='http://q.followenjoy.cn/#/';
        // $host='http://192.168.1.251/#/';
        
        // if($user['user_id'] && $user['user_id'] != $user['unionid']){
        //     // 用户id存在，可以直接登录，绑定 unionid
        //     $user_id=$user['user_id'];
        //     $token=createToken($user_id);
        //     $url=$host."HomePage?user_id=$user_id&token=$token&backUrl=$backUrl&shop_id=$shop_id";
        
        //     if($shop_id){
        //         // 需要绑定上级
        //         // ===================================================================================
        //         // 绑定 shopID
        //         // 先看看有没有绑定，已经绑定过了就不要再绑定
        //         $UserSuper=D('UserSuper');
        
        //         $where=[];
        //         $where['user_id']=$user['user_id'];
        //         $isSuper=$UserSuper->where($where)->find();
        //         if(!$isSuper){
        //             // 不存在才添加
        //             $where=[];
        //             $where['shop_id']=$shop_id;
        //             $shopUser=$User->where($where)->find();
        //             if($shopUser){
        //                 // shop用户存在
        //                 // 绑定
        //                 $data=[];
        //                 $data['user_id']=$user['user_id'];
        //                 $data['super_id']=$shopUser['user_id'];
        //                 $data['add_time']=time();
        //                 $data['edit_time']=time();
        
        //                 $UserSuper->add($data);
        
        //             }
        
        //         }
        
        //     }
        
        // }else{
        //     // 用户id不存在,需要绑定手机号
        //     $url=$host."WeiXinLogin?unionid=$unionid&backUrl=$backUrl&shop_id=$shop_id";
        // }
        
        
    }
    
    
    public function test2(){
        
        $TimeGoods=D('TimeGoods');
        $list=$TimeGoods->select();
        
        $start_time=time();
        
        $end_time=strtotime("+1 day",$start_time);
        
        $time1=date('Y-m-d H:i:s',$start_time);
        $time2=date('Y-m-d H:i:s',$end_time);
        
        
        foreach ($list as $k => $v) {
            
            
            $start_time=$v['start_time'];
            $end_time=$v['end_time'];
            
            
            $end_time=strtotime("+1 day",$start_time);
            $v['end_time']=$end_time;
            
            dump($start_time);
            dump($end_time);
            dump(date('Y-m-d H:i:s',$start_time));
            dump(date('Y-m-d H:i:s',$end_time));
            
            
            echo '<hr/>';
            
            $data=$v;
            
            $where=[];
            $where['time_goods_id']=$v['time_goods_id'];
            
            // $TimeGoods->where($where)->save($data);
            // dump($v);
            
        }
        
        
        
    }
    
    public function init(){
        
        die;
        $userList=[];
        
        $User=D('User');
        $User->where('1=1')->delete();
        
        
        for ($i=1; $i <=100 ; $i++) {
            $item['user_id']=$i;
            $item['user_name']=$i;
            $item['user_pwd']='6ee5d358e0f29a6e44514b8deaec46c5';
            $item['user_type']='0';
            $item['user_vip_level']=$i===1?0:1;
            $item['user_money']=$i===1?10000:0;
            $item['course_hours']=0;
            $item['add_time']=time();
            $item['edit_time']=time();
            $userList[]=$item;
        }
        
        $User->addAll($userList);
        
        
        $UserSuper=D('UserSuper');
        
        $UserSuper->where('1=1')->delete();
        
        
        for ($i=0; $i <count($userList); $i++) {
            
            
            $item=$userList[$i];
            
            if($item['user_id'] != 0 && $item['user_id'] % 10 == 0){
                $start=($item['user_id']-10);
                
                if($item['user_id']+10<count($userList)){
                    $subList=[
                    [
                    'user_id'=>$item['user_id'],
                    'super_id'=>$item['user_id']+10,
                    'add_time'=>time(),
                    'edit_time'=>time(),
                    ]
                    ];
                }
                
                for ($j=$start; $j < $item['user_id']-1; $j++) {
                    
                    $sub=$userList[$j];
                    
                    $a=[];
                    $a['user_id']= $sub['user_id'];
                    $a['super_id']= $item['user_id'];
                    $a['add_time']=time();
                    $a['edit_time']= time();
                    
                    $subList[]=$a;
                    
                    
                }
                $UserSuper->addAll($subList);
                
            }
            
        }
        
        echo "生成完成~";
        
    }
    
    public function js(){
        die;
        $level=[
        '1',//J
        '1',//I
        '1',//H
        '1',//G
        '2',//F
        '2',//E
        '2',//D
        '3',//C
        '3',//B
        '3',//A
        ];
        
        $User=D('User');
        
        for ($i=1; $i <= 10; $i++) {
            
            $save=[];
            $save['user_vip_level']=$level[$i-1];
            
            $where=[];
            $where['user_id'] = [['gt',($i-1)*10-1],['lt',$i*10]];
            
            $User->where($where)->save($save);
            
        }
        
        
        
    }
    public function test(){
        die;
        Vendor('VIP.VipPlus');
        
        $conf=[];
        $conf['userId']='10';
        $conf['isDebug']=true;
        $conf['isSave']=false;
        
        $vip=new \VipPlus($conf);
        // $vip->出货得佣金(100);
        
        echo ('<hr/>');
        
        $conf['userId']='1';
        $conf['isDebug']=true;
        $conf['isSave']=false;
        $sub=new \VipPlus($conf);
        
        $vip->获得邀人得钱奖($sub);
        
        
        die;
        Vendor('VIP.VIP');
        
        //初始化vip对象
        $conf=[];
        $conf['userId']='13914896237';
        $conf['isDebug']=true;
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        
        //==================省钱回扣功能展示============================
        ec("==================省钱回扣功能展示============================");
        //订单的原本总价`
        $orderMoney=100;
        //自购省钱，当此用户购买了一个商品并且支付成功后，调用此函数，调用后上级得到回扣。
        $vip->discountRebate($orderMoney);
        
        
        // //==================下级列表功能展示============================
        // ec("==================下级列表功能展示============================");
        // //初始化下级列表
        // $vip->initSubList();
        // //获得下级列表
        // $subList=$vip->getSubList();
        // // dump($subList);
        
        //==================销售奖功能展示============================
        ec("==================销售奖功能展示============================");
        
        //订单的原本总价
        $orderMoney=100;
        // 什么时候调用？
        // 当分享出去的商品被购买后，先取得分享人的 vip 对象，然后取得分享人 vip 对象的 super，
        // 然后调用这个 super 的 salesAward() 函数。
        
        //现在，假设分享人的id为 12132，那么调用当前对象的 super的指定函数
        $vip->getSuper()->salesAward($orderMoney);
        
        
        //==================自己发展团队功能展示============================
        ec("==================自己发展团队功能展示============================");
        
        // //当 12132 这个用户成功支付后，创建这个用户的 vip 对象，然后取得当前用户的 super ，然后调用 super 的 邀请人得钱奖()
        
        $conf=[];
        $conf['userId']='13914896237';
        $conf['isDebug']=true;
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        $vip->getSuper()->邀请人得钱奖($vip);
        
        for ($i=0; $i < 50; $i++) {
            ec ('');
        }
        
        
    }
    
    public function pay(){
        
        ec('支付测试','h2');
        
        $PayLog=D('PayLog');
        // alipay();
        
    }
    
    public function vip(){
        
        // // 2018052000273318721
        // $Fission=D('Fission');
        // $is=$Fission->validate('2018052000273318721');
        // if($is){
        //     $Fission->handle($is,'13914896237');
        // }
        Vendor('VIP.VIP');
        
        //初始化vip对象
        $conf=[];
        $conf['userId']='13914896237';
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        
        //==================省钱回扣功能展示============================
        ec("==================省钱回扣功能展示============================");
        //订单的原本总价`
        // $orderMoney=100;
        //自购省钱，当此用户购买了一个商品并且支付成功后，调用此函数，调用后上级得到回扣。
        // $vip->discountRebate($orderMoney);
        
        
        // //==================下级列表功能展示============================
        // ec("==================下级列表功能展示============================");
        // //初始化下级列表
        // $vip->initSubList();
        // //获得下级列表
        // $subList=$vip->getSubList();
        // // dump($subList);
        
        //==================销售奖功能展示============================
        ec("==================销售奖功能展示============================");
        
        //订单的原本总价
        // $orderMoney=100;
        // 什么时候调用？
        // 当分享出去的商品被购买后，先取得分享人的 vip 对象，然后取得分享人 vip 对象的 super，
        // 然后调用这个 super 的 salesAward() 函数。
        
        //现在，假设分享人的id为 12132，那么调用当前对象的 super的指定函数
        // $vip->getSuper()->salesAward($orderMoney);
        
        
        //==================自己发展团队功能展示============================
        ec("==================自己发展团队功能展示============================");
        
        // //当 12132 这个用户成功支付后，创建这个用户的 vip 对象，然后取得当前用户的 super ，然后调用 super 的 邀请人得钱奖()
        
        $conf=[];
        $conf['userId']='13914896237';
        $vip=new \VIP($conf);
        $vip->setDebug(true);
        $vip->setWriteDatabase(false);
        $vip->getSuper()->邀请人得钱奖($vip);
        
        for ($i=0; $i < 50; $i++) {
            ec ('');
        }
    }
    
}