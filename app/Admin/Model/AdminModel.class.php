<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
    
    public $field=[
    'admin_id',
    'power_group_id',
    'admin_name',
    'admin_head_img',
    'add_time',
    'edit_time',
    ];
    
    public function _initialize (){}
    
    public function creat($data){
        $data['admin_pwd']=md5($data['admin_pwd'].__KEY__);
        $data['add_time']=time();
        $data['edit_time']=time();
        return $this->add($data);
    }
    
    
    public function saveData($admin_id,$data){
        
        $where=[];
        $where['admin_id']=$admin_id;
        if($data['admin_pwd']){
            $data['admin_pwd']=md5($data['admin_pwd'].__KEY__);
        }
        $data['edit_time']=time();
        return $this->where($where)->save($data);
        
    }
    
    public function get($admin_id){
        
        $where=[];
        $where['admin_id']=$admin_id;
        
        $data= $this
        ->where($where)
        ->field($this->field)
        ->find();
        
        $data['power']=$this->getPower($data['power_group_id']);
        return $data;
    }
    
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->field($this->field)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        foreach ($list as $k => $v) {
            //找权限组信息
            $v['power']=$this->getPower($v['power_group_id']);
            $list[$k]=$v;
        }
        
        $list=toTime($list);
        return $list;
    }
    
    public function getPower($power_group_id){
        $PowerGroup=D('PowerGroup');
        return $PowerGroup->get($power_group_id);
    }
    
    public function del($ids){
        $where=[];
        $where['admin_id']=['in',$ids];
        return $this->where($where)->delete();
        
    }
    
    public function getUserInfo(){
        
        $where=[];
        $where['admin_id']=session('admin_id');
        $data=$this->where($where)->field($this->field)->find();
        $data['power']=$this->getPower($data['power_group_id']);
        return $data;
        
    }
}