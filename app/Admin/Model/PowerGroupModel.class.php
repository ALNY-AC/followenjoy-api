<?php
namespace Admin\Model;
use Think\Model;
class PowerGroupModel extends Model {
    
    
    public $PowerGroupProps;
    
    public function _initialize (){
        $this->PowerGroupProps=D('PowerGroupProps');
    }
    
    public function creat($data){
        
        $data['power_group_id']=getMd5('power_group');
        $data['add_time']=time();
        $data['edit_time']=time();
        
        return $this->add($data);
        
    }
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list=$this
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->where($where)
        ->select();
        
        return $this->buliderList($list);
    }
    
    
    public function get($power_group_id){
        
        $where=[];
        $where['power_group_id']=$power_group_id;
        
        $data=$this->where($where)->find();
        
        if(!$data){
            return null;
        }
        
        $power_group_props=$this->getProps($power_group_id);
        
        // ===================================================================================
        // 组装权限组编号数据
        $nums=[];
        foreach ($power_group_props as $k => $v) {
            $nums[]=$v['power_num']['power_num']+0;
        }
        
        $data['power_group_props']=$power_group_props;
        $data['nums']=$nums;
        return $data;
    }
    
    public function getProps($power_group_id){
        $PowerNum=D('PowerNum');
        $where=[];
        $where['power_group_id']=$power_group_id;
        $list=$this->PowerGroupProps
        ->order('add_time desc')
        ->where($where)
        ->select();
        
        // ===================================================================================
        // 取得所有编号
        
        foreach ($list as $k => $v) {
            $power_num_id=$v['power_num_id'];
            $v['power_num'] =  $PowerNum->get($power_num_id);
            $list[$k]=$v;
        }
        
        $list=toTime($list);
        return $list;
    }
    
    public function del($ids){
        $where=[];
        $where['power_group_id']=['in',$ids];
        $is1=$this->where($where)->delete();
        $is2=$this->PowerGroupProps->where($where)->delete();
        return $is1!==false && $is2!==false;
    }
    
    
    public function saveData($power_group_id,$data){
        $where=[];
        $where['power_group_id']=$power_group_id;
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        $list=$this
        ->order('add_time desc')
        ->where($where)
        ->select();
        return $this->buliderList($list);
    }
    
    public function buliderList($list){
        if(!$list){
            return [];
        }
        foreach ($list as $k => $v) {
            $v['power_group_props']=$this->getProps($v['power_group_id']);
            $list[$k]=$v;
        }
        $list=toTime($list);
        return $list;
    }
    
    
    
    
}