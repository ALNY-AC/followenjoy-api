<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月25日09:48:52
* 最新修改时间：2018年7月25日09:48:52
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####展位模型#####
* @author 代码狮
*
*/
namespace Admin\Model;
use Think\Model;
class BoothModel extends Model {
    
    
    public function create($data){
        $data['booth_id']=getMd5('booth');
        $data['add_time']=time();
        $data['edit_time']=time();
        return $this->add($data);
    }
    
    public function get($id){
        $where=[];
        $where['booth_id']=$id;
        return $this->where($where)->find();
    }
    
    public function del($ids){
        $where=[];
        $where['booth_id']=['in',getIds($ids)];
        return $this->where($where)->delete();
    }
    
    public function saveData($ids,$data){
        $where=[];
        $where['booth_id']=['in',getIds($ids)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function getAll(){
        
        $where=I('where')?I('where'):[];
        $list= $this->where($where)->select();
        return  $list;
    }
    
}