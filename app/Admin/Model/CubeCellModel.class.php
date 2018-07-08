<?php

/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月3日14:04:12
* 最新修改时间：2018年7月3日14:04:12
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####魔方单元格模型#####
* @author 代码狮
*
*/

namespace Admin\Model;
use Think\Model;
class CubeCellModel extends Model {
    
    public function create($data){
        
        $data['cube_cell_id']=getMd5('cube_cell_id');
        $data['add_time']=time();
        $data['edit_time']=time();
        $data['data_status']=1;
        
        
        return $this->add($data);
    }
    
    public function del($id,$is_recycle){
        
        $where=[];
        $where['cube_cell_id']=['in',getIds($id)];
        
        if($is_recycle){
            $data=[];
            $data['data_status']=0;
            return $this->where($where)->save($data);
        }else{
            return $this->where($where)->delete();
        }
        
    }
    
    public function getAll($data){
        
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        if(!$field){
            $field=[
            'cube_cell_id',
            'cube_row_id',
            'x',
            'img_url',
            'link_type',
            'link_id',
            ];
        }
        $where['data_status']=1;
        
        $list  =  $this
        ->order('x asc')
        ->where($where)
        ->field($field)
        ->select();
        $list=$this->builder($list);
        return $list;
        
        
    }
    
    public function get($id,$field){
        
        
        if(!$field){
            $field=[
            'cube_cell_id',
            'cube_row_id',
            'x',
            'img_url',
            'link_type',
            'link_id',
            ];
        }
        
        
        $where=[];
        $where['ceub_cell_id']=$id;
        $data=$this
        ->where($where)
        ->field($field)
        ->find();
        return $data;
        
    }
    
    public function saveData($id,$data){
        $where=[];
        $where['cube_cell_id']=['in',getIds($id)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    
    public function builder($list){
        
        return $list;
    }
    
    
}