<?php
namespace Home\Model;
use Think\Model;
class SpecialModel extends Model {
    
    
    public function getList($data){
        $where  =   $data['where']?$data['where']:[];
        
        $field=[
        'special_id',
        'special_title',
        // 'special_info',
        'special_logo',
        'special_head',
        'goods_type',
        // 'add_time',
        // 'edit_time',
        'content_type',
        'color',
        'sort',
        ];
        
        $list=$this
        ->order('sort asc')
        ->field($field)
        ->where($where)
        ->limit($limit)
        ->select();
        return $list;
        
    }
    
    //获得单个，包括商品等数据
    public function get($special_id){
        
        //获得基本信息
        $where=[];
        $where['special_id']=$special_id;
        $special=$this->where($where)->find();
        
        //获得商品、专题关联表
        $SpecialGoods=D('SpecialGoods');
        $goodss=$SpecialGoods->distinct(true)->where($where)->select();
        
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