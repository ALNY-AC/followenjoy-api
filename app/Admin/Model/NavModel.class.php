<?php
namespace Admin\Model;
use Think\Model;
class NavModel extends Model {
    
    
    
    public function getList($data){
        $navList=$this->select();
        return $navList;
    }
    public function getHome($data){
        
    }
    
    
    //获得单个，包括商品、专题等数据
    public function get($nav_id){
        
        $where=[];
        $where['nav_id']=$nav_id;
        $nav=$this->where($where)->find();
        
        
        //找专题
        $NavSpecial=D('NavSpecial');
        $where['nav_id']=$nav_id;
        $specials=$NavSpecial->where($where)->select();
        $Special=D('Special');
        $nav['specials']=[];
        for ($i=0; $i < count($specials); $i++) {
            $special_id=$specials[$i]['special_id'];
            $where=[];
            $where['special_id']=$special_id;
            $special=$Special->where($where)->find();
            $nav['specials'][]=$special;
        }
        
        //找商品
        $NavGoods=D('NavGoods');
        $where['nav_id']=$nav_id;
        $specials=$NavGoods->where($where)->select();
        $Goods=D('Goods');
        $nav['goodsList']=[];
        for ($i=0; $i < count($specials); $i++) {
            $goods_id=$specials[$i]['goods_id'];
            $special=$Goods->get($goods_id);
            $nav['goodsList'][]=$special;
        }
        
        return $nav;
        
        
    }
    
    
}