<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####订单管理控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class OrderController extends CommonController{
    
    public function creatPrintData(){
        
        $Order=D('Order');
        $download_id=$Order->creatPrintData();
        $res['res']=1;
        $res['msg']=$download_id;
        //组装url发送
        
        $url=U('printData',['download_id'=>$download_id],'',true);
        
        $res['url']=$url;
        
        echo json_encode($res);
    }
    public function printData(){
        $Order=D('Order');
        $Order->printData(I('get.download_id'));
    }
    //获得总数
    public function getCount(){
        $model=M('order');
        $count=$model->count();
        $res['res']=$count+0;
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
    }
    //获得列表
    public function getList(){
        
        $Order=D('Order');
        $data=I();
        $where=$data['where'];
        
        if($where['start_time']){
            $where['add_time'] = [['gt',$where['start_time']],['lt',$where['end_time']]];
            unset($where['start_time']);
            unset($where['end_time']);
        }
        
        $key=$data['key'];
        if($key){
            
            $keys=$key;
            //先根据空格分割为数组
            $keys = explode(" ", $keys);
            $keys = array_filter($keys);  // 删除空元素
            
            foreach ($keys as $k => $v) {
                $keys[$k]='%'.$v.'%';
            }
            $group=$data['group'];
            $where[$group]=['like',$keys,'OR'];
            
        }
        
        
        $data['where']=$where;
        
        $result=$Order->getList($data);
        
        $res['count']=$Order->where($where)->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    public function get(){
        $Order=D('Order');
        
        $result=$Order->get(I('order_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    //保存字段
    public function saveData(){
        
        $where=I('where');
        $Order=D('Order');
        $result=$Order->saveData(I('order_id'),I('save','',false));
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function del(){
        $Order=D('Order');
        
        $result=$Order->del(I('ids'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getListPlus(){
        
        // ===================================================================================
        // 组建基本参数
        $Order=D('Order');
        $data=I();
        $page_size=I('page_size',5);
        $page=I('page',1);
        
        $where=$data['where'];
        
        if($where['start_time']){
            $where['add_time'] = [['gt',$where['start_time']],['lt',$where['end_time']]];
            unset($where['start_time']);
            unset($where['end_time']);
        }
        
        $where=getKey();
        
        // ===================================================================================
        // 创建模型
        $Supplier=D('Supplier');// 供货商
        $Order=D('Order');// 订单
        $Pay=D('Pay');// 订单
        $Snapshot=D('Snapshot');//快照
        $OrderCoupon=D('OrderCoupon');//优惠券订单关联表
        $OrderAddress=D('OrderAddress');//收货地址库
        
        // ===================================================================================
        // 先取订单
        $orderList=$Order
        ->field('*')
        ->where($where)
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        // ===================================================================================
        // 统计总数
        
        $res['total']=$Order
        ->where($where)
        ->count()+0;
        
        // ===================================================================================
        // 组成订单的id
        $orderIds=[];
        foreach ($orderList as $k => $v) {
            $orderIds[]=$v['order_id'];//组成订单的id
            $addressIds[]=$v['address_id'];//组成地址id
            $payIds[]=$v['pay_id'];//组成支付id
            $supplierIds[]=$v['supplier_id'];//组成供货商id
        }
        if(count($orderIds)>0){
            
            // ===================================================================================
            // 取快照
            $where=[];
            $where['order_id']=['in',getIds($orderIds)];
            $snapshotList=$Snapshot
            ->where($where)
            ->field('*')
            ->select();
            
            // ===================================================================================
            // 取收货地址
            $where=[];
            $where['address_id']=['in',getIds($addressIds)];
            $addressList=$OrderAddress
            ->where($where)
            ->field('*')
            ->select();
            
            // ===================================================================================
            // 取支付信息
            $where=[];
            $where['pay_id']=['in',getIds($payIds)];
            $payList=$Pay
            ->where($where)
            ->field('*')
            ->select();
            
            // ===================================================================================
            // 取出供货商信息
            // supplier_id
            $where=[];
            $where['supplier_id']=['in',getIds($supplierIds)];
            $supplierList=$Supplier
            ->where($where)
            ->field('*')
            ->select();
            
            // ===================================================================================
            // 将快照等信息拼接回到订单中
            foreach ($orderList as $k => $v) {
                $v['add_time']=date('Y/m/d H:i:s',$v['add_time']);
                // ===================================================================================
                // 拼回快照
                foreach ($snapshotList as $x => $z) {
                    if($z['order_id']==$v['order_id']){
                        $z['skuInfo']=$this->getSkuInfo($z);
                        unset($z['s1']);
                        unset($z['s2']);
                        unset($z['s3']);
                        $v['goodsInfo']=$z;
                    }
                }
                
                // ===================================================================================
                // 拼回收货地址
                foreach ($addressList as $x => $z) {
                    $z['info']=$z['province'].$z['city'].$z['county'].$z['address_detail'];
                    if($z['address_id']==$v['address_id']){
                        $v['addressInfo']=$z;
                    }
                }
                
                // ===================================================================================
                // 拼回支付信息
                foreach ($payList as $x => $z) {
                    if($z['pay_id']==$v['pay_id']){
                        $v['payInfo']=$z;
                    }
                }
                // ===================================================================================
                // 拼回供货商信息
                foreach ($supplierList as $x => $z) {
                    if($z['supplier_id']==$v['supplier_id']){
                        $v['supplierInfo']=$z;
                    }
                }
                
                $orderList[$k]=$v;
                
            }
        }
        
        
        
        // ===================================================================================
        // 输出数据
        if($orderList!==false){
            $res['res']=count($orderList);
            $res['msg']=$orderList;
        }else{
            $res['res']=-1;
            $res['msg']=$orderList;
        }
        echo json_encode($res);
    }
    
    /**
    * 组装sku信息
    */
    private function getSkuInfo($skus=[]){
        $info='';
        for ($i=1; $i <=3 ; $i++) {
            if($skus['s'.$i]){
                $info.=$skus['s'.$i];
                if($skus['s'.($i+1)]){
                    $info.=' - ';
                }
            }
        }
        return $info;
    }
    
}