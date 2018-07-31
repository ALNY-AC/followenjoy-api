<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月25日09:40:13
* 最新修改时间：2018年7月25日09:40:13
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####展位组模型#####
* @author 代码狮
*
*/
namespace Admin\Model;
use Think\Model;
class BoothGroupModel extends Model {
    
    
    public function create($data){
        $booth_group_id=getMd5('booth_group');
        $data['booth_group_id']=$booth_group_id;
        $data['add_time']=time();
        $data['edit_time']=time();
        return $this->add($data);
    }
    
    public function get($id){
        $where=[];
        $where['booth_group_id']=$id;
        return $this->where($where)->find();
    }
    
    public function del($ids){
        $where=[];
        $where['booth_group_id']=['in',getIds($ids)];
        $Booth=D('Booth');
        $save=[];
        $save['booth_group_id']='0';
        $Booth->where($where)->save($save);
        return $this->where($where)->delete();
    }
    
    public function getList(){
        $page   =   $data['page']?$data['page']:1;
        $page_size  =   $data['page_size']?$data['page_size']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $list = toTime($list);
        $Booth=D('Booth');
        
        foreach ($list as $k => $v) {
            // booth_sub_count
            
            $where=[];
            $where['booth_group_id']=$v['booth_group_id'];
            $v['booth_sub_count']=$Booth->where($where)->count()+0;
            $list[$k]=$v;
            
        }
        
        return $list;
        
    }
    
    public function saveData($ids,$data){
        $where=[];
        $where['booth_group_id']=['in',getIds($ids)];
        unset($data['add_time']);
        $data['edit_time']=time();
        return $this->where($where)->save($data);
    }
    
    public function getAll(){
        $where=I('where')?I('where'):[];
        return $this->where($where)->select($data);
    }
    
}