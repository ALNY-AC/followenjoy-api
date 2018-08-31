<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月14日13:00:11
* 最新修改时间：2018年5月14日13:00:11
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####支付宝支付控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class AlipayController extends Controller{
    
    public function pay(){
        
        $pay_id=I('pay_id');
        
        // ===================================================================================
        // 模型
        $Pay=D('Pay');
        $Order=D('Order');
        $Snapshot=D('Snapshot');
        // ===================================================================================
        // 取得支付单数据
        $where=[];
        $where['pay_id']=$pay_id;
        $payData=$Pay->where($where)->find();
        
        // ===================================================================================
        // 根据支付单取得订单数据
        $where=[];
        $where['pay_id']=$pay_id;
        $orderList=$Order->where($where)->field('order_id')->select();
        // 将订单id取出
        $orderIds=[];
        foreach ($orderList as $k => $v) {
            $orderIds[]=$v['order_id'];
        }
        
        // ===================================================================================
        // 根据订单取出快照
        $where=[];
        $where['order_id']=['in',$orderIds];
        $snapshotList= $Snapshot->field('goods_title,s1,s2,s3')->where($where)->select();
        
        // ===================================================================================
        // 组建描述信息
        $body="";
        
        foreach ($snapshotList as $k => $v) {
            $body.=$v['goods_title'].' - ';
        }
        
        $subject=rtrim($body, " - ");
        echo strlen($body);
        // 128
        $data=[];
        $data['body']='【随享季】';
        $data['subject']=$subject;
        $data['out_trade_no']=$pay_id;
        $data['timeout_express']='1h';
        $data['total_amount']=$payData['price'];
        $data['product_code']='QUICK_WAP_WAY';
        
        if(IS_DEBUG){
            $notify_url='http://test.server.followenjoy.cn/index.php/Home/Alipay/shopping_notify';
        }else{
            $notify_url='http://server.followenjoy.cn/index.php/Home/Alipay/shopping_notify';
        }
        
        $conf['notify_url']=$notify_url;//异步通知：购物异步通知
        alipay($pay_id,$data,$conf,IS_DEBUG);
        
    }
    
    public function validate(){
        // trade_status
        
        // ===================================================================================
        // 创建模型
        $PayLog=D('PayLog');
        
        // ===================================================================================
        // 检查是否有支付日志单
        $pay_id=I('pay_id');
        $where=[];
        $where['pay_id']=$pay_id;
        $log=$PayLog->where($where)->find();
        
        if($log!==false){
            
            if($log===null){
                // 暂无数据
                $res['res']=0;
            }else{
                // 有数据，验证状态
                if($log['trade_status']=="TRADE_SUCCESS"){
                    // 支付成功
                    $res['res']=1;
                    $res['msg']='TRADE_SUCCESS';
                }else{
                    $res['res']=-2;
                    $res['msg']='CLOSE';
                }
                
            }
            
        }else{
            // 数据查询出错
            $res['res']=-1;
        }
        
        echo json_encode($res);
    }
    
    // 异步通知
    public function shopping_notify(){
        // trade_status
        $trade_status=I('trade_status');
        
        // ===================================================================================
        // 接收数据
        $out_trade_no=I('out_trade_no');//商家订单号
        $buyer_pay_amount=I('buyer_pay_amount');//支付金额
        
        // 交易支付成功
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');
        $PayLog=D('PayLog');
        
        // ===================================================================================
        // 创建条件
        $where=[];
        $where['pay_id']=$out_trade_no;
        // ===================================================================================
        $payData=$Pay->where($where)->find();
        
        // ===================================================================================
        // 创建支付日志数据
        $user_id=$payData['user_id'];
        
        $data=[];
        $data['trade_status']=$trade_status;//支付状态
        $data['pay_id']=$out_trade_no;
        $data['user_id']=$user_id;
        $data['price']=$buyer_pay_amount;//支付金额
        $data['pay_mode']='alipay';//支付方式
        $data['type']='shopping';//此交易类型，0：购物，1：充值
        $data['log']="[支付宝支付]";//日志信息
        $data['info']='';//日志描述信息
        $data['data']=json_encode(I(''));//接口传来的数据
        $data['remark']='';//备注信息，用户或Admin输入
        
        $result=$PayLog->creat($data);
        
        if($result){
            // 日志创建成功
            if($trade_status=='TRADE_SUCCESS'){
                if($payData['state']!=1){
                    
                    $Pay->setPayType($out_trade_no,1);
                    // 支付成功
                    $Pay->setState($out_trade_no,1);
                    // 0：未支付
                    // 1：已支付
                    // 2：已取消
                    
                    //                    // 一元商品
                    $where=[];
                    $where['pay_id']=$out_trade_no;
                    $Order=D('Order');
                    $order=$Order->where($where)->select();
                    $orderIds=[];
                    foreach ($order as $k => $v) {
                        $orderIds[]=$v['order_id'];
                    }
                    
                    $Snapshot=D('Snapshot');
                    
                    $where=[];
                    $where['order_id']=['in',$orderIds];
                    
                    $snapshot=$Snapshot->where($where)->select();
                    
                    $OneGoodsUser=D('OneGoodsUser');
                    
                    $OneGoods=D('OneGoods');
                    $is=$OneGoods->isGoods($v['goods_id']);
                    
                    foreach ($snapshot as $k => $v) {
                        $is=$OneGoods->isGoods($v['goods_id']);
                        
                        if($is){
                            // ===================================================================================
                            // 是一元商品，需要加入到表中
                            $data=[];
                            $data['user_id']=$user_id;
                            $data['goods_id']=$v['goods_id'];
                            $OneGoodsUser->add($data);
                        }
                        
                    }
                    
                }
                
            }
            echo 'success';
        }else{
            echo 'error';
        }
    }
    
    public function test(){
        $PayLog=D('PayLog');
        $list=$PayLog->select();
        
        foreach ($list as $k => $v) {
            $v['data']=json_decode($v['data'],true);
            $list[$k]=$v;
        }
        dump($list);
        
    }
    
}