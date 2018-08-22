<?php
namespace Home\Model;
use Think\Model;
class NavModel extends Model {
    
    
    public function getList($data){
        
        $navList=$this
        ->order('sort asc,add_time asc')
        ->where('is_show = 1')
        ->cache(true,120)
        ->select();
        return $navList;
    }
    
    // 找导航对应的商品
    public function getGoods($nav_id,$data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        
        $NavGoods=D('NavGoods');
        $where=[];
        $where['nav_id']=$nav_id;
        $navs=$NavGoods
        ->cache(true,120)
        ->where($where)
        ->select();
        $ids=[];
        foreach ($navs as $k => $v) {
            $ids[]=$v['goods_id'];
        }
        $where=[];
        $where['goods_id']=['in',$ids];
        $Goods=D('Goods');
        $list=[];
        if($ids){
            $list=$Goods->getList($data,$where);
        }
        
        return $list;
        
    }
    
    
    //获得单个，包括商品、专题等数据
    public function get($nav_id){
        
        $where=[];
        $where['nav_id']=$nav_id;
        $nav=$this->where($where)->find();
        
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