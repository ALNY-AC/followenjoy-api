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
    
    public function index(){
        echo "随享季1";
    }
    
    public function test(){
        
        
        $Goods=D('Goods');
        
        
        $field=[
        'goods_id',
        'goods_title',
        // 'goods_banner',
        // 'sub_title',
        // 'freight_id',
        // 'is_up',
        // 'goods_class',
        // 'sort',
        // 'is_cross_border',
        'goods_content',
        // 'is_unique',
        // 'add_time',
        // 'edit_time'
        ];
        $where['goods_id']=171;
        $list=$Goods
        ->field($field)
        ->where($where)
        ->order('goods_id asc')
        // ->limit(2)
        ->select();
        
        $saveArr=[];
        foreach ($list as $k => $v) {
            
            $goods_content=$v['goods_content'];
            
            // $v['goods_content']=str_replace('hyu2595240001.my3w.com','server.followenjoy.cn',$goods_content);
            $goods_content=str_replace('<p>','',$goods_content);
            $goods_content=str_replace('</p>','',$goods_content);
            $v['goods_content']=$goods_content;
            
            $item=[];
            $item['goods_id']=$v['goods_id'];
            $item['save']=['goods_content'=>$v['goods_content']];
            $saveArr[]=$item;
            
        }
        
        foreach ($saveArr as $k => $v) {
            $where=[];
            $goods_id=$v['goods_id'];
            $where['goods_id']=$v['goods_id'];
            $save=$v['save'];
            // $is=$Goods->where($where)->save($save);
            if($is){
                ec("商品【 $goods_id 】保存成功",'p','color:#0f0');
            }else{
                ec("商品【 $goods_id 】保存失败！",'p','color:#f00');
            }
            
        }
        
        
        dump($list);
        dump($saveArr);
        
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