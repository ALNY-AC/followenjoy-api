<?php
namespace Admin\Model;
use Think\Model;
class SpecialPaperModel extends Model {
    
    public function creat($data){
        $special_paper_id=getMd5('special_paper');
        $data['special_paper_id']=$special_paper_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        return $this->add($data);
    }
    
    public function del($ids){
        $where=[];
        $where['special_paper_id']=['in',$ids];
        return $this->where($where)->delete();
        
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=toTime($list);
        return $list;
        
    }
    
    public function getAll($data){
        
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->select();
        return $list;
        
    }
    
    public function get($special_paper_id){
        $where=[];
        $where['special_paper_id']=$special_paper_id;
        return $this->where($where)->find();
    }
    
    public function saveData($special_paper_id,$data){
        
        $where=[];
        $where['special_paper_id']=$special_paper_id;
        $data['edit_time']=time();
        return $this->where($where)->save($data);
        
    }
    
}