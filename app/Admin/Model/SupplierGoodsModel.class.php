<?php
namespace Admin\Model;
use Think\Model;
class SupplierGoodsModel extends Model {
    
    public function _initialize (){}
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $supplierGoodss  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        // ======================================================
        //找商品数据
        $Goods=D('Goods');
        foreach ($supplierGoodss as $key => $goods) {
            $goods_id=$goods['goods_id'];
            $goods=$Goods->get($goods_id);
            $supplierGoodss[$key]['goods_info']=$goods;
        }
        $supplierGoodss=toTime($supplierGoodss);
        return $supplierGoodss;
        
    }
    
    //移除指派
    public function del($supplier_id,$goods_id){
        
        $Goods=D('Goods');
        // ======================================================
        // 修改商品的供货商id为空
        $where=[];
        $where['goods_id']=['in',$goods_id];
        $where['supplier_id']=$supplier_id;
        $save['supplier_id']=null;
        $result=$Goods->where($where)->save($save);
        return $result;
    }
    
    
}