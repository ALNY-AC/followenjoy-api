<?php
namespace Home\Model;
use Think\Model;
class NavModel extends Model {
    
    
    public function getList($data){
        
        $navList=$this
        ->order('add_time asc')
        ->where('is_show = 1')
        ->select();
        return $navList;
    }
    
    // 找导航对应的商品
    public function getGoods($nav_id,$data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        
        
        $NavGoods=D('NavGoods');
        $where['nav_id']=$nav_id;
        $navs=$NavGoods->where($where)->select();
        $ids=[];
        foreach ($navs as $k => $v) {
            $ids['goods_id']=$v['goods_id'];
        }
        
        $where=[];
        $where['goods_id']=['in',$ids];
        $Goods=D('Goods');
        $result=$Goods->getList($data,$where);
        return $result;
        
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
        
        
        // ===================================================================================
        // 找到所有id
        $ids=[];
        for ($i=0; $i < count($specials); $i++) {
            $goods_id=$specials[$i]['goods_id'];
            $ids[]=$goods_id;
        }
        
        // ===================================================================================
        // 找商品
        if($ids){
            $where=[];
            $where['goods_id']=['in',$ids];
            $data=[];
            $data['where']=$where;
            $nav['goodsList']=$Goods->getAll($data);
        }else{
            $nav['goodsList']=[];
        }
        
        return $nav;
    }
    
    
}