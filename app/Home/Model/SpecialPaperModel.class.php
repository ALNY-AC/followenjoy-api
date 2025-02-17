<?php
namespace Home\Model;
use Think\Model;
class SpecialPaperModel extends Model {
    
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['page_size']?$data['page_size']:10;
        $where  =   $data['where']?$data['where']:[];
        
        
        $field=[
        'special_paper_id',
        'special_id',
        'paper_head',
        'paper_title',
        'paper_info',
        // 'paper_content',
        'add_time',
        // 'edit_time',
        ];
        
        $where['release_time']= ['ELT',time()];//当前时间到达发布时间
        $list  =  $this
        ->order('release_time desc,add_time desc')
        // ->cache(true,180)
        ->field($field)
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=toTime($list);
        return $list;
        
    }
    
    public function getAll($data){
        
        $where  =   $data['where']?$data['where']:[];
        
        $field=[
        'special_paper_id',
        'special_id',
        'paper_head',
        'paper_title',
        'paper_info',
        // 'paper_content',
        'add_time',
        // 'edit_time',
        ];
        $list  =  $this
        ->order('add_time desc')
        ->field($field)
        ->where($where)
        ->select();
        return $list;
        
    }
    
    public function get($special_paper_id){
        $where=[];
        $where['special_paper_id']=$special_paper_id;
        
        $data=$this->where($where)->find();
        $data=toTime([$data])[0];
        if($data['goods_id']){
            // 商品存在，找商品
            $Goods=D('Goods');
            $goods=$Goods->get($data['goods_id']);
            $data['goods']=$goods;
        }else{
            $data['goods']=null;
        }
        
        return $data;
    }
    
}