<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月15日09:49:41
* 最新修改时间：2018年4月15日09:49:41
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品快照模型#####
* @author 代码狮
*
*/
namespace Home\Model;
use Think\Model;
class SnapshotModel extends Model {
    
    public function _initialize (){}
    
    //保存一个优惠券给快照
    public function saveCoupon($snapshot_id,$coupon_id){
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $save=[];
        $save['coupon_id']=$coupon_id;
        
        $Coupon=D('Coupon');//优惠券
        //判断是否可以使用
        $discount=$Coupon->getCouponPrice($coupon_id,$snapshot_id,false);
        if($discount>0){
            //可以使用
            $result=$this->where($where)->save($save);
            return $result;
        }else{
            //不可以使用
            return false;
        }
    }
    
    
    
    //添加一个快照信息
    public function create($goods_id,$sku_id,$count=1){
        
        $Sku=D('Sku');
        $Goods=D('goods');
        //==================================================================
        //如果有的order_id 为null，则直接返回这个null 的数据
        $user_id=session('user_id');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['sku_id']=$sku_id;
        $where['user_id']=$user_id;
        $where['order_id']=['EXP','is NULL'];
        $result=$this->where($where)->find();
        //==================================================================
        //如果添加过就不用再次添加
        if(!$result){
            
            //未添加.
            //==================================================================
            //取得sku
            $where=[];
            $where['sku_id']=$sku_id;
            $sku=$Sku->where($where)->find();
            //==================================================================
            //取得商品
            $where=[];
            $where['goods_id']=$goods_id;
            $goods=$Goods->get($goods_id,['img_list']);
            //==================================================================
            //组成添加数据
            $goods_title=$goods['goods_title'];
            $img=$goods['img_list'][0];
            $snapshot_id=getMd5('snapshot');
            $add=[];
            $add['snapshot_id']=$snapshot_id;
            $add['goods_id']=$goods_id;
            $add['img']=$img['src'];
            $add['goods_title']=$goods_title;
            $add['sku_id']=$sku_id;
            $add['s1']=$sku['s1'];
            $add['s2']=$sku['s2'];
            $add['s3']=$sku['s3'];
            $add['tax']=$sku['tax'];
            $add['price']=$sku['price'];
            
            $add['earn_price']=$sku['earn_price'];
            $add['purchase_price']=$sku['purchase_price'];
            $add['shop_code']=$sku['shop_code'];
            
            
            $add['user_id']=session('user_id');
            $add['count']=$count+0;
            $add['add_time']=time();
            $add['edit_time']=time();
            //==================================================================
            //添加到数据库中
            $result=$this->add($add);
        }else{
            //已添加，返回已添加的id，并且追加数量
            $snapshot_id=$result['snapshot_id'];
            $where=[];
            $where['snapshot_id']=$snapshot_id;
            $this->where($where)->setInc('count',$count);
        }
        
        return $snapshot_id;
    }
    
    
    //取得一个
    public function get($snapshot_id){
        //==================================================================
        //创建模型
        $Goods=D('Goods');
        //==================================================================
        
        $where=[];
        $where['snapshot_id']=$snapshot_id;
        $snapshot=$this->where($where)->find();
        //取得商品数据
        $goods_info;
        $goods_id=$snapshot['goods_id'];
        $goods_info=$Goods->get($goods_id);
        $snapshot['goods_info']=$goods_info;
        
        return $snapshot;
    }
    
    
    public function getList($snapshot_ids){
        $arr=[];
        foreach ($snapshot_ids as $key => $id) {
            $arr[]=$this->get($id);
        }
        return $arr;
        
    }
    
    
    
}