<?php
namespace Admin\Model;
use Think\Model;
class SupplierModel extends Model {
    
    public function _initialize (){}
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $suppliers  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $suppliers=toTime($suppliers);
        return $suppliers;
        
    }
    
    public function getAll($data){
        $where  =   $data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->select();
        $list=toTime($list);
        return $list;
    }
    
    
    public function del($ids){
        $where=[];
        $where['supplier_id']=['in',$ids];
        $result=$this->where($where)->delete();
        return $result;
    }
    
    public function create($add){
        
        // ======================================================
        // 先判断是否已经有了
        $where=[];
        $where['supplier_login_id']=$add['supplier_login_id'];
        $result=$this->where($where)->find();
        if (!$result) {
            //不存在，可以添加
            $add['source']=1;//手动添加的来源
            $add['supplier_id']=getMd5('supplier');
            $add['state']=2;
            $add['add_time']=time();
            $add['edit_time']=time();
            $result=$this->add($add);
            return $result!==false;
            
        }else{
            return 'is no null';
        }
    }
    
    public function get($supplier_id){
        $where=[];
        $where['supplier_id']=$supplier_id;
        $result=$this->where($where)->find();
        return $result;
    }
    
    public function saveData($where,$save){
        
        //删除一些字段
        unset($save['supplier_id']);
        unset($save['add_time']);
        
        //修改一些字段
        $save['edit_time']=time();
        
        $result=$this->where($where)->save($save);
        return $result;
        
    }
    
}