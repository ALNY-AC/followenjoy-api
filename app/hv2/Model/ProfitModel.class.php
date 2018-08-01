<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月24日07:49:25
* 最新修改时间：2018年5月24日07:49:25
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户收支明细#####
* @author 代码狮
*
*/
namespace Home\Model;
use Think\Model;
class ProfitModel extends Model {
    
    public function create($data){
        $profit_id=getMd5('profit');
        $data['profit_id']=$profit_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        $this->add($data);
        return $this->where(["profit_id"=>$profit_id])->find();
    }
    
    public function get($id){
        $where=[];
        $where['profit_id']=$profit_id;
        $data= $this->where($where)->find();
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
    
    private function bulider($list){
        $list=toTime($list);
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
    
    public function saveData($id,$data){
        $where=[];
        $where['profit_id']=['in',getIds($id)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return  $this->where($where)->save($data);
    }
    
    public function del($id){
        $where=[];
        $where['profit_id']=['in',getIds($id)];
        return  $this->where($where)->delete();
    }
    
    
}