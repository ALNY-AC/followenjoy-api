<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月3日13:54:56
* 最新修改时间：2018年7月3日13:54:56
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####魔方行模型#####
* @author 代码狮
*
*/
namespace Admin\Model;
use Think\Model;
class CubeRowModel extends Model {
    
    public function create($data){
        
        $cube_row_id=getMd5('cube_row_id');
        $data['cube_row_id']=$cube_row_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        $data['data_status']=1;
        
        
        // ===================================================================================
        //  创建4个单元格
        
        $CubeCell=D('CubeCell');
        
        
        for ($i=0; $i < 4; $i++) {
            
            $cellData=[];
            
            $cellData['cube_row_id']=$cube_row_id;
            $cellData['x']=$i;
            $cellData['img_url']='';
            $cellData['link_type']='';
            $cellData['link_id']='';
            $CubeCell->create($cellData);
            
        }
        
        
        return $this->add($data);
        
    }
    
    public function get($id,$field){
        
        $where=[];
        $where['cube_row_id']=$id;
        
        if(!$field){
            $field=[
            'cube_row_id',
            'cube_group_id',
            'sort',
            ];
        }
        // 这里要携带魔方单元格数据
        
        $data=$this
        ->field($field)
        ->where($where)
        ->find();
        
        return $data;
    }
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        $where['data_status']=1;
        
        if(!$field){
            $field=[
            'cube_row_id',
            'cube_group_id',
            'sort',
            ];
        }
        
        $list=$this
        ->order('sort asc')
        ->field($field)
        ->where($where)
        ->select();
        
        
        $list=$this->bulider($list);
        return $list;
        
    }
    
    public function del($id,$is_recycle=true,$data_status){
        
        
        // ===================================================================================
        // 创建模型
        $CubeCell=D('CubeCell');
        
        // ===================================================================================
        // 创建条件
        $where=[];
        $where['cube_row_id']=['in',getIds($id)];
        
        $data=[];
        $data['data_status']=$data_status;
        
        if($is_recycle){
            // 逻辑删除
            
            if($this->where($where)->save($data)){
                return $CubeCell->where($where)->save($data);
            }else{
                return false;
            }
            
        }else{
            // 物理删除
            if($this->where($where)->delete()){
                return $CubeCell->where($where)->delete();
            }else{
                return false;
            }
        }
        
    }
    
    public function saveData($id,$data){
        $where=[];
        $where['cube_row_id']=['in',getIds($id)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    
    public function bulider($list){
        
        $CubeCell=D('CubeCell');
        
        foreach ($list as $k => $v) {
            
            // ===================================================================================
            // 取出单元格数据
            $data=[];
            $data['where']=[];
            $data['where']['cube_row_id']=$v['cube_row_id'];
            $cells=$CubeCell->getAll($data);
            $v['cells']=$cells;
            $list[$k]=$v;
        }
        
        return $list;
        
    }
    
    
}