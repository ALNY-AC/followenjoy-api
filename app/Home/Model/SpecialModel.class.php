<?php
namespace Home\Model;
use Think\Model;
class SpecialModel extends Model {
    
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where['is_small']=0;
        $specials=$this
        ->order('sort asc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        return $specials;
        
    }
    
    public function getSmallList($data){
        
        $limit  =   $data['limit']?$data['limit']:6;
        $where  =   $data['where']?$data['where']:[];
        
        $where['is_small']=1;
        
        $specials=$this
        ->order('sort asc')
        ->where($where)
        ->limit($limit)
        ->select();
        return $specials;
    }
    
    //获得单个，包括商品等数据
    public function get($special_id){
        
        //获得基本信息
        $where=[];
        $where['special_id']=$special_id;
        $special=$this->where($where)->find();
        
        //获得商品、专题关联表
        $SpecialGoods=D('SpecialGoods');
        $goodss=$SpecialGoods->where($where)->select();
        
        $Goods=D('Goods');
        
        $special['goodsList']=[];
        
        // 展示内容
        // 0：商品
        // 1：文章
        for ($i=0; $i < count($goodss); $i++) {
            $goods_id=$goodss[$i]['goods_id'];
            $goods=$Goods->get($goods_id);
            $special['goodsList'][]=$goods;
        }
        
        //获得对应的商品信息
        return $special;
    }
    
}