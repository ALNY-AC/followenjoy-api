<?php
namespace Home\Model;
use Think\Model;
class BannerModel extends Model {
    
    
    
    public function get($id){
        $where=[];
        $where['banner_id']=$id;
        $data=$this
        ->where($where)
        ->find();
        $data=$this->bulider([$data])[0];
        return $data;
    }
    
    public function getList($data){
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        $list  =  $this
        // ->cache(true,60)
        ->order('sort asc,add_time desc')
        ->where($where)
        ->field($field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $list=$this->bulider($list);
        return $list;
    }
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        $list  =  $this
        // ->cache(true,60)
        ->order('sort asc,add_time desc')
        ->where($where)
        ->field($field)
        ->select();
        $list=$this->bulider($list);
        return $list;
    }
    
    
    public function bulider($list){
        
        // foreach ($list as $k => $v) {
        // }
        
        $list=toTime($list);
        return $list;
    }
    
}