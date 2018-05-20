<?php
namespace Admin\Model;
use Think\Model;
class TempPagesModel extends Model {
    
    public function creat($data){
        
        
        // ===================================================================================
        // 创建基本字段
        $temp_pages_id=getMd5('temp_pages');
        $data['temp_pages_id']=$temp_pages_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        
        // ===================================================================================
        // 将tree转换为字符串
        
        return $this->add($data);
    }
    
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
    
    public function del($ids){
        $where=[];
        $where['temp_pages_id']=['in',getIds($ids)];
        return $this->where($where)->delete();
    }
    
    public function saveData($ids,$data){
        $where=[];
        $where['temp_pages_id']=['in',getIds($ids)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    private function bulider($data){
        
        foreach ($data as $k => $v) {
            $data[$k]=$v;
        }
        
        $data=toTime($data);
        return $data;
    }
    
    
}