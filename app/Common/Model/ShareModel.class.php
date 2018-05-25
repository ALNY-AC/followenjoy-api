<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月25日15:34:24
* 最新修改时间：2018年5月25日15:34:24
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分享模型#####
* @author 代码狮
*
*/
namespace Common\Model;
use Think\Model;
class ShareModel extends Model {
    
    
    // 创建分享
    public function creat($data){
        
        $share_id=getMd5('share_id');
        
        $data['share_id']=$share_id;
        $data['user_id']=session('user_id');
        $data['add_time']=time();
        $data['edit_time']=time();
        
        $is=$this->add($data);
        
        if($is){
            return $share_id;
        }else{
            return false;
        }
        
    }
    
    // 取得分享数据
    public function get($share_id){
        $where['share_id']=$share_id;
        $data=$this->where($where)->field();
        $data=toTime([$data])[0];
        return $data;
    }
    
    
    // 取得此用户所有的分享数据
    public function getAll($data){
        $where=$data['where']?$data['where']:[];
        $list=$this->where($where)->select();
        $list=toTime($list);
        return $list;
    }
    
    
    // 分页查询此用户的分享
    public function getList($data){
        $page=$data['page']?$data['page']:1;
        $limit=$data['limit']?$data['limit']:10;
        $where=$data['where']?$data['where']:[];
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $list=toTime($list);
        return $list;
    }
    
    // 删除分享数据，删除后管理的分享数据将无法使用，连接将丢失
    public function del($pay_log_id){
        $where['share_id']=['in',getIds($share_id)];
        return $this->where($where)->delete();
    }
    
    // 更新分享的数据
    public function saveData($share_id,$data){
        $where['share_id']=['in',getIds($share_id)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    
}