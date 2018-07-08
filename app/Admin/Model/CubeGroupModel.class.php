<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月3日12:01:22
* 最新修改时间：2018年7月3日12:01:22
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####魔方组模型#####
* @author 代码狮
*
*/
namespace Admin\Model;
use Think\Model;
class CubeGroupModel extends Model {
    
    public function create($data){
        
        $cube_group_id=getMd5('cube_group_id');
        $data['cube_group_id']=$cube_group_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        $data['data_status']=1;
        
        // ===================================================================================
        // 创建两个行
        $CubeRow=D('CubeRow');
        $rowData=[];
        $rowData['cube_group_id']=$cube_group_id;
        $rowData['sort']=0;
        $CubeRow->create($rowData);
        $rowData['sort']=1;
        $CubeRow->create($rowData);
        
        
        return $this->add($data);
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        $where['data_status']=1;
        
        
        if(!$field){
            $field=[
            'cube_group_id',
            'cube_group_title',
            'cube_group_message',
            'add_time',
            ];
        }
        
        
        $list  =  $this
        ->order('add_time desc')
        ->field($field)
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=$this->builder($list);
        return $list;
        
    }
    
    public function getAll($data){
        
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        $where['data_status']=1;
        
        if(!$field){
            $field=[
            'cube_group_id',
            'cube_group_title',
            'cube_group_message',
            'add_time',
            ];
        }
        
        $list  =  $this
        ->order('add_time desc')
        ->field($field)
        ->where($where)
        ->select();
        $list=$this->builder($list);
        return $list;
    }
    
    public function get($id,$field){
        
        if(!$field){
            $field=[
            'cube_group_id',
            'cube_group_title',
            'cube_group_message',
            ];
        }
        
        $where=[];
        $where['ceub_group_id']=$id;
        $data=$this
        ->where($where)
        ->field($field)
        ->find();
        
        
        // ===================================================================================
        // 取出行
        $CubeRow=D('CubeRow');
        $rowData=[];
        $rowData['where']=[];
        $rowData['where']['cube_group_id']=$id;
        $rows=$CubeRow->getAll($rowData);
        $data['rows']=$rows;
        return $data;
    }
    
    public function del($ids,$is_recycle=true,$data_status=0){
        
        // ===================================================================================
        // 创建模型
        $CubeRow=D('CubeRow');
        
        // ===================================================================================
        // 取得行ids
        
        $where=[];
        $where['ceub_group_id']=['in',getIds($ids)];
        $cube_row_id_arr=$CubeRow->where($where)->getField('cube_row_id',true);
        
        
        if($is_recycle){
            // 逻辑删除
            $data=[];
            $data['data_status']=$data_status;
            
            $where=[];
            $where['ceub_group_id']=['in',getIds($ids)];
            // 删除组
            if($this->where($where)->save($data)!==false){
                // 删除行
                return $CubeRow->del($cube_row_id_arr,$is_recycle,$data_status);
                
            }else{
                return false;
            }
            
            
        }else{
            // 物理删除
            
            $where=[];
            $where['ceub_group_id']=['in',getIds($ids)];
            // 删除组
            if($this->where($where)->delete()){
                // 删除行
                $where=[];
                $where['cube_row_id']=['in',getIds($cube_row_id_arr)];
                if($CubeRow->where($where)->delete()){
                    // 删除单元格
                    $where=[];
                    $where['cube_cell_id']=['in',getIds($cube_cell_id_arr)];
                    return  $CubeCell->where($where)->delete();
                }else{
                    return false;
                }
                
            }else{
                return false;
            }
            
        }
    }
    
    public function saveData($id,$data){
        $where=[];
        $where['ceub_group_id']=['in',getIds($id)];
        
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function builder($list){
        $list=toTime($list);
        return $list;
    }
    
    
}