<?php
namespace Home\Model;
use Think\Model;
class NavModel extends Model {
    
    
    public function getList($data){
        $navList=$this
        ->order('add_time asc')
        ->select();
        return $navList;
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