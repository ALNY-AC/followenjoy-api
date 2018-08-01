<?php
namespace Home\Model;
use Think\Model;
class TempPagesModel extends Model {
    
    
    public function get($id){
        $where=[];
        $where['temp_pages_id']=$id;
        $data=$this->where($where)->find();
        return $this->bulider([$data])[0];
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $field=[
        'temp_pages_id',
        'temp_pages_title',
        'temp_pages_info',
        // 'tree',
        'head',
        'add_time',
        // 'edit_time',
        ];
        
        $list  =  $this
        ->order('add_time desc')
        ->field($field)
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        return $this->bulider($list);
        
    }
    
    public function getAll($data){
        $where=$data['where']?$data['where']:[];
        
        $field=[
        'temp_pages_id',
        'temp_pages_title',
        'temp_pages_info',
        // 'tree',
        'head',
        'add_time',
        // 'edit_time',
        ];
        
        $list=$this
        ->order('add_time desc')
        ->field($field)
        ->where($where)
        ->select();
        
        return $this->bulider($list);
    }
    
    
    private function bulider($data){
        
        foreach ($data as $k => $v) {
            $data[$k]=$v;
        }
        
        $data=toTime($data);
        return $data;
    }
    
    
}