<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月8日16:42:41
* 最新修改时间：2018年6月8日16:42:41
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####微信支付控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class WeiXinController extends Controller{
    
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
        
        $data=[];
        $data['body']='随享季-购物';
        $data['out_trade_no']=$pay_id;
        $data['total_fee']=$payData['price'];
        
        // 商家名称-销售商品类目
        
        $jsApiParameters=weixin($data);
        $this->assign('jsApiParameters',$jsApiParameters);
        $this->display();
        // server.followenjoy.cn/index.php/Home/WeiXin/pay
    }
    
    public function notify(){
        
        
        // Vendor('Weixin.WxPayJsApiPay');
        // Vendor('Weixin.WxPayData');
        Vendor('Weixin.WxPayApi');
        Vendor('Weixin.WxPayNotify');
        
        $xml = file_get_contents("php://input");
        
        $wx_notified_data=\WxPayDataBase::FromXml_4_babbage ($xml) ;
        
        // 转成数组 并写入缓存
        F ( "wx_notified_data", $wx_notified_data);
        // 吧xml原型也写入xml
        F ( "wx_notified_data_xml", $xml );
        
        $log=json_encode($wx_notified_data);
        $time=date('Y-m-d H:i:s',time());
        
        \Think\Log::write("[微信支付 - $time]：". $log,'WARN');
        
        
        
        $out_trade_no=I('out_trade_no');//商家订单号
        $total_fee=I('total_fee');//支付金额
        
        
        // ===================================================================================
        // 创建模型
        $Pay=D('Pay');
        $PayLog=D('PayLog');
        
        // ===================================================================================
        // 创建条件
        $where=[];
        $where['pay_id']=$out_trade_no;
        
        
        // ===================================================================================
        // 取出支付单数据
        $payData=$Pay->where($where)->find();
        
        // ===================================================================================
        // 创建支付日志数据
        $user_id=$payData['user_id'];
        
        $data=[];
        $data['trade_status']=$wx_notified_data['result_code'];//支付状态
        $data['pay_id']=$out_trade_no;
        $data['user_id']=$user_id;
        $data['price']=$total_fee;//支付金额
        $data['pay_mode']='weixin';//支付方式
        $data['type']='shopping';//此交易类型，0：购物，1：充值
        $data['log']="[微信支付]";//日志信息
        $data['info']='';//日志描述信息
        $data['data']=json_encode(I(''));//接口传来的数据
        $data['remark']='';//备注信息，用户或Admin输入
        
        $result=$PayLog->creat($data);
        if($result){
            // 日志创建成功
            if($wx_notified_data['result_code']=='SUCCESS'){
                //支付成功
                // 支付成功
                if($payData['state']!=1){
                    // 如果已经支付成功，就不能再支付成功
                    
                    $Pay->setState($out_trade_no,1);
                    // 0：未支付
                    // 1：已支付
                    // 2：已取消
                    
                    
                    // 减库存
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
                    $Sku=D('Sku');
                    
                    foreach ($snapshot as $k => $v) {
                        $count=$v['count'];
                        $sku_id=$v['sku_id'];
                        $where=[];
                        $where['sku_id']=$sku_id;
                        $Sku->where($where)->setDec('stock_num',$count);
                    }
                    
                }
                
            }else{
                
            }
            
        }
        
        
        
        
        // 给微信返回支付状态值
        $notify = new \WxPayNotify ();
        // 返回状态
        $notify->Handle ( false );
        
    }
    
    
    
    public function test(){
        
        $wx_notified_data= F('wx_notified_data');
        $wx_notified_data_xml= F('wx_notified_data_xml');
        
        printf_info($wx_notified_data);
        
        dump($wx_notified_data);
        dump($wx_notified_data_xml);
        
        /*
        
        appid : wx56a5a0b6368f00a7
        attach : test
        bank_type : CFT
        cash_fee : 1
        fee_type : CNY
        is_subscribe : Y
        mch_id : 1501688321
        nonce_str : jrat62n117gv9pbvi0v2g4pnop9b6zhm
        openid : o7T0S1kWxodopu0siad7be8hw1wo
        out_trade_no : 1528748198
        result_code : SUCCESS
        return_code : SUCCESS
        sign : FDF9D56B6131C0ED962E7B41B084A4E8
        time_end : 20180612041643
        total_fee : 1
        trade_type : JSAPI
        transaction_id : 4200000117201806128925456553
        
        
        ================================================================================================================
        
        $xml=
        "<xml><appid><![CDATA[wx56a5a0b6368f00a7]]></appid>".
        "<attach><![CDATA[test]]></attach>".
        "<bank_type><![CDATA[CFT]]></bank_type>".
        "<cash_fee><![CDATA[1]]></cash_fee>".
        "<fee_type><![CDATA[CNY]]></fee_type>".
        "<is_subscribe><![CDATA[Y]]></is_subscribe>".
        "<mch_id><![CDATA[1501688321]]></mch_id>".
        "<nonce_str><![CDATA[gj8le6yefczuz37srift0j6kxes1y0ho]]></nonce_str>".
        "<openid><![CDATA[o7T0S1kWxodopu0siad7be8hw1wo]]></openid>".
        "<out_trade_no><![CDATA[1528746664]]></out_trade_no>".
        "<result_code><![CDATA[SUCCESS]]></result_code>".
        "<return_code><![CDATA[SUCCESS]]></return_code>".
        "<sign><![CDATA[62CA2D84572E198ED836C9C0721E9588]]></sign>".
        "<time_end><![CDATA[20180612035109]]></time_end>".
        "<total_fee>1</total_fee>".
        "<trade_type><![CDATA[JSAPI]]></trade_type>".
        "<transaction_id><![CDATA[4200000125201806120649994853]]></transaction_id>".
        "</xml>";
        
        <xml><appid><![CDATA[wx56a5a0b6368f00a7]]></appid>
        <attach><![CDATA[test]]></attach>
        <bank_type><![CDATA[CFT]]></bank_type>
        <cash_fee><![CDATA[1]]></cash_fee>
        <fee_type><![CDATA[CNY]]></fee_type>
        <is_subscribe><![CDATA[Y]]></is_subscribe>
        <mch_id><![CDATA[1501688321]]></mch_id>
        <nonce_str><![CDATA[jrat62n117gv9pbvi0v2g4pnop9b6zhm]]></nonce_str>
        <openid><![CDATA[o7T0S1kWxodopu0siad7be8hw1wo]]></openid>
        <out_trade_no><![CDATA[1528748198]]></out_trade_no>
        <result_code><![CDATA[SUCCESS]]></result_code>
        <return_code><![CDATA[SUCCESS]]></return_code>
        <sign><![CDATA[FDF9D56B6131C0ED962E7B41B084A4E8]]></sign>
        <time_end><![CDATA[20180612041643]]></time_end>
        <total_fee>1</total_fee>
        <trade_type><![CDATA[JSAPI]]></trade_type>
        <transaction_id><![CDATA[4200000117201806128925456553]]></transaction_id>
        </xml>
        
        ================================================================================================================
        
        array(17) {
        ["appid"] => string(18) "wx56a5a0b6368f00a7"
        ["attach"] => string(4) "test"
        ["bank_type"] => string(3) "CFT"
        ["cash_fee"] => string(1) "1"
        ["fee_type"] => string(3) "CNY"
        ["is_subscribe"] => string(1) "Y"
        ["mch_id"] => string(10) "1501688321"
        ["nonce_str"] => string(32) "jrat62n117gv9pbvi0v2g4pnop9b6zhm"
        ["openid"] => string(28) "o7T0S1kWxodopu0siad7be8hw1wo"
        ["out_trade_no"] => string(10) "1528748198"
        ["result_code"] => string(7) "SUCCESS"
        ["return_code"] => string(7) "SUCCESS"
        ["sign"] => string(32) "FDF9D56B6131C0ED962E7B41B084A4E8"
        ["time_end"] => string(14) "20180612041643"
        ["total_fee"] => string(1) "1"
        ["trade_type"] => string(5) "JSAPI"
        ["transaction_id"] => string(28) "4200000117201806128925456553"
        }
        
        
        */
        
    }
    
    public function getsignkey(){
        
        
        getsignkey();
        
        
        
    }
}