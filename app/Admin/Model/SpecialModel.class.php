<?php
namespace Admin\Model;
use Think\Model;
class SpecialModel extends Model {
    
    // 新建专题
    public function creat($add){
        $add['special_id']=getMd5('special');
        $add['add_time']=time();
        $add['edit_time']=time();
        return  $this->add($add);
    }
    
    // 保存专题数据
    public function saveData($special_id,$save){
        
        $where=[];
        $where['special_id']=$special_id;
        $save['edit_time']=time();
        unset($save['add_time']);
        unset($save['special_id']);
        
        return $this->where($where)->save($save);
        
    }
    
    // 取专题分页数据
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list=$this
        ->order('sort asc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        foreach ($list as $k => $v) {
            // $v['goods']=$this->getGoods($v['special_id']);
            // $v['paper']=$this->getPaper($v['special_id']);
            $list[$k]=$v;
        }
        $list=toTime($list);
        return $list;
        
    }
    
    // 取一个专题
    public function get($special_id){
        
        $where=[];
        $where['special_id']=$special_id;
        
        $special=$this->where($where)->find();
        
        if(!$special){
            return null;
        }
        
        $special['goods']=$this->getGoods($special_id);
        $special['paper']=$this->getPaper($special_id);
        
        return $special;
        
    }
    
    // 取全部专题内容
    public function getAll($ids){
        
        $where=[];
        $where['special_id']=['in',$ids];
        
        
        $list=$this
        ->order('sort desc')
        ->where($where)
        ->select();
        $list=toTime($list);
        
        foreach ($list as $k => $v) {
            $list[$k]=$v;
        }
        return $list;
    }
    
    
    // ===================================================================================
    // 文章操作
    
    public function addPaper($special_id,$special_paper_id){
        
        $SpecialPaper=D('SpecialPaper');
        
        $where=[];
        $where['special_paper_id']=['in',$special_paper_id];
        
        $save=[];
        $save['special_id']=$special_id;
        return $SpecialPaper->where($where)->save($save);
        
    }
    
    // 取得文章列表
    public function getPaper($special_id){
        $SpecialPaper=D('SpecialPaper');
        $where=[];
        $where['special_id']=$special_id;
        $list= $SpecialPaper->where($where)->select();
        // $list=toTime($list);
        return $list;
    }
    
    // 删除专题
    public function del($ids){
        $where=[];
        $where['special_id']=['in',$ids];
        
        $SpecialGoods=D('SpecialGoods');
        //删除关联商品
        $SpecialGoods->where($where)->delete();
        
        return $this->where($where)->delete();
    }
    
    // ===================================================================================
    // 商品操作
    
    
    // 删除商品
    public function delGoods($special_id,$goods_id){
        
        $SpecialGoods=D('SpecialGoods');
        $where=[];
        $where['special_id']=$special_id;
        $where['goods_id']=['in',$goods_id];
        return $SpecialGoods->where($where)->delete();
        
    }
    
    // 添加商品
    public function addGoods($special_id,$goods_id){
        if(!$goods_id){
            return null;
        }
        $SpecialGoods=D('SpecialGoods');
        
        $adds=[];
        foreach ($goods_id as $k => $v) {
            $item=[];
            $item['special_id']=$special_id;
            $item['goods_id']=$v;
            $adds[]=$item;
        }
        
        return $SpecialGoods->addAll($adds,'',true);
    }
    // 取一个专题的商品
    public function getGoods($special_id){
        
        $Goods=D('Goods');
        $SpecialGoods=D('SpecialGoods');
        
        $where=[];
        $where['special_id']=$special_id;
        
        $ids=$SpecialGoods->where($where)->getField('goods_id');
        if(!$ids){
            return [];
        }
        return $Goods->getAll($ids);
    }
    
    
}