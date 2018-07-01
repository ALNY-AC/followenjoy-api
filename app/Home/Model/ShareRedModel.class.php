<?php
namespace Home\Model;
use Think\Model;
class ShareRedModel extends Model {
    
    
    public function create($data=[]){
        
        
        $user_id=I('user_id');
        
        $share_red_id=getMd5('share_red');
        $data['share_red_id']=$share_red_id;
        $data['user_id']=I('user_id');
        $data['price']=10;
        $data['balance']=10;
        $data['max_index']=7;
        $data['max_price']=5;
        $data['max_length']=7;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $is=$this->add($data);
        
        
        if($is){
            // 生成url
            $p=[];
            $p['share_red_id']=$share_red_id;
            $url=U('ShareRed/init',$p,'',true);
            return $url;
        }else{
            return false;
        }
    }
    
    public function pull($post){
        // ===================================================================================
        // 接收参数
        $share_red_id=$post['share_red_id'];
        $user_id=$post['phone'];
        
        $unionid=session('unionid');
        $nickname=session('nickname');
        $headimgurl=session('headimgurl');
        
        // ===================================================================================
        // 创建模型
        $ShareRedRecord=D('ShareRedRecord');//红包领取记录模型
        $User=D('User');//红包领取记录模型
        $UserSuper=D('UserSuper');//用户关联表
        
        // ===================================================================================
        // 取出分享数据
        $where=[];
        $where['share_red_id']=$share_red_id;
        $share=$this->where($where)->find();
        
        // ===================================================================================
        // 取出分享人信息
        $shareUserId=$share['user_id'];
        // $where=[];
        // $where['user_id']=$shareUserId;
        // $shareUser=$User->where($where)->find();
        
        // ===================================================================================
        // 先看看有没有这个人，没有这个人就自动注册
        $where=[];
        $where['user_id']=$user_id;
        
        $user=$User->where($where)->find();
        if(!$user){
            // 没有用户，就注册
            // 没有创建新用户
            $superData=[];
            $superData['user_id']=$user_id;
            $superData['super_id']=$shareUserId;
            $UserSuper->add($superData);
            
            $data=[];
            $data['user_id']=$user_id;
            $data['user_name']=$nickname;
            $data['user_head']=$headimgurl;
            $data['unionid']=$unionid;
            $data['user_vip_level']=0;
            $data['user_money']=0;
            $data['add_time']=time();
            $data['edit_time']=time();
            $User->add($data);
            $user=$User->where($where)->find();
            
        }else{
            // 有用户，检查绑定了微信没有，没有绑定就绑定微信
            
            $where=[];
            $where['user_id']=$user_id;
            $data=[];
            $data['user_name']=$nickname;
            $data['user_head']=$headimgurl;
            $data['unionid']=$unionid;
            $user=$User->where($where)->save($data);
            
        }
        
        
        
        
        // ===================================================================================
        // 组成红包获取数据
        $propleCount=$share['max_length'];
        $money=$share['price'];
        $maxMoney=$share['max_price'];
        $length=$ShareRedRecord->where($where)->count()+0;
        $isEnd=$length+1>=$propleCount-1;
        
        // ===================================================================================
        // 不能重复领取
        $where=[];
        $where['share_red_id']=$share_red_id;
        $where['user_id']=$user_id;
        $is = $ShareRedRecord->where($where)->find();
        if($is){
            //领取过，不能重复领取
            $res['res']=-3;
            echo json_encode($res);
            die;
        }
        
        // ===================================================================================
        // 统计已经领取多少
        // $where=[];
        // $where['share_red_id']=$share_red_id;
        // $sumScore = $ShareRedRecord->where($where)->sum('price');
        
        $infoList=[
        '哎呀呀，就差一点点成为手气王~',
        '鸿运当头,可惜猪脚不是你~',
        '运气不够？就试试直购吧',
        '换个姿势，金额会更高哦~',
        '老夫回家攒攒人品再来~',
        '哎呦，没有最佳有你也不错',
        ];
        
        $rand=rand(0,4);
        
        $info=$infoList[$rand];
        
        if($length>=$propleCount){
            // 红包已经发完了
            $res['res']=-2;
        }else{
            // 红包还可以领取
            // ===================================================================================
            // 如果六个人已经领取
            if($length==$propleCount-1){
                // 前几个人已经领取，就差最后一个人，直接给最大红包
                // 并且红包已经分享状态
                $data['index']=$length+1;
                $data['share_red_id']=$share_red_id;
                $data['user_id']=$user_id;
                $data['price']=$maxMoney;
                $data['unionid']=session('unionid');
                $data['nickname']=session('nickname');
                $data['headimgurl']=session('headimgurl');
                $data['dev_value']=0;
                $data['info']=$info;
                $isRecord=$ShareRedRecord->create($data);
                $isBalance=$this->where($where)->setDec('balance',$maxMoney); // 减去余额
                if($isRecord && $isBalance!==false){
                    
                    // 成功
                    $res['res']=1;
                    $res['msg']=$isRecord;
                    $this->createRed($user_id,$maxMoney,true);
                    
                }else{
                    // 失败
                    $res['res']=-1;
                }
            }else{
                
                // ===================================================================================
                // 取出上一个人的记录
                $where=[];
                $where['share_red_id']=$share_red_id;
                $where['index']=$length;
                $front= $ShareRedRecord->where($where)->find();
                if(!$front){
                    // 上一个人的记录没有，那这个人就是第一个人
                    $devValue=0;
                }else{
                    $devValue=$front['dev_value'];
                }
                // dump([$propleCount,$money,$devValue,$maxMoney,$isEnd]);
                $price=$this->getOneMoney($propleCount,$money,$maxMoney,$devValue,$isEnd);
                
                // dump($price);
                $data['index']=$length+1;
                $data['share_red_id']=$share_red_id;
                $data['user_id']=$user_id;
                $data['price']=$price[1];
                $data['unionid']=session('unionid');
                $data['nickname']=session('nickname');
                $data['headimgurl']=session('headimgurl');
                $data['dev_value']=$price[0];
                $data['info']=$info;
                $isRecord= $ShareRedRecord->create($data);
                
                $where=[];
                $where['share_red_id']=$share_red_id;
                $isBalance=$this->where($where)->setDec('balance',$price[1]); // 减去余额
                
                // dump($isRecord);
                // dump($isBalance);
                if($isRecord && $isBalance!==false){
                    // 成功
                    $res['res']=1;
                    $res['msg']=$isRecord;
                    $this->createRed($user_id,$price[1],false);
                }else{
                    // 失败
                    $res['res']=-1;
                }
            }
        }
        
        echo json_encode($res);
        
        die;
        // $ShareRedRecord=D('ShareRedRecord');
        // return $ShareRedRecord->pull();
    }
    
    public function createRed($user_id,$money,$isMax){
        
        $Coupon=D('Coupon');
        
        if($isMax){
            // 最大红包
            $coupon=$Coupon->获得满减券(30,$money,1,2,0,'','拼手气红包-全场通用');
            
        }else{
            // 其他红包
            $coupon=$Coupon->获得满减券(0,$money,1,2,0,'','拼手气红包-全场通用');
            
        }
        
        $coupon['user_id']=$user_id;
        $coupon['add_time']=time();
        $coupon['edit_time']=time();
        $Coupon->add($coupon);
        
    }
    
    /**
    * @param $propleCount 总人数
    * @param $money 总钱数
    * @param $maxMoney 最大可得钱数
    * @param $a 修正值
    * @param $isEnd 是否是最后一个人
    *
    */
    public function getOneMoney($propleCount,$money,$maxMoney,$a=0,$isEnd){
        
        $peoples=$propleCount-1;
        $peoplesMoney=$money-$maxMoney;
        $min=($peoplesMoney-$maxMoney)/($propleCount-1);
        $arg=$peoplesMoney/$peoples;
        
        $c=($arg-0.1)/($peoples-1);//最后一个平均分给其他的值
        
        if($isEnd){
            
            // dump($a);
            $b= 0.1+$a;
            $b=sprintf("%.2f",substr(sprintf("%.2f", $b*100), 0, -2))/100;
            // $b=sprintf("%.1f",$b);
            
        }else{
            
            $b=randomFloat($min,$arg+$a+$c);
            $b=sprintf("%.2f",substr(sprintf("%.2f", $b*100), 0, -2))/100;
            // $b=sprintf("%.1f",$b);
        }
        
        $a=$arg+$a+$c-$b;
        // $a=sprintf("%.1f",$a);
        
        return [$a,$b];
    }
    
    
}