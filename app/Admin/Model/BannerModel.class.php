<?php
namespace Admin\Model;
use Think\Model;
class BannerModel extends Model {
    
    
    public function creat($data){
        $banner_id=getMd5('banner');
        $data['banner_id']=$banner_id;
        $data['edit_time']=time();
        $data['add_time']=time();
        return $this->add($data);
    }
    
    public function get($id){
        $where=[];
        $where['banner_id']=$id;
        $data=$this->where($where)->find();
        $data=$this->bulider([$data])[0];
        return $data;
    }
    
    public function getList($data){
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        $list  =  $this
        ->order('add_time desc')
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
        ->order('add_time desc')
        ->where($where)
        ->field($field)
        ->select();
        $list=$this->bulider($list);
        return $list;
    }
    public function del($ids){
        $where=[];
        $where['banner_id']=['in',getIds($ids)];
        return $this->where($where)->delete();
    }
    
    public function saveData($ids,$data){
        $where=[];
        $where['banner_id']=['in',getIds($ids)];
        return $this->where($where)->save($data);
    }
    
    public function bulider($list){
        
        foreach ($list as $k => $v) {
            
            
        }
        
        $list=toTime($list);
        return $list;
    }
    
}