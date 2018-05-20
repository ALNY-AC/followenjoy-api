<?php
namespace Home\Model;
use Think\Model;
class ChatModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($data){
        $chat_id=getMd5('chat');
        $data['chat_id']=$chat_id;
        $data['user_id']=session('user_id');
        $data['role']=2;//1是客服，2是客户
        $data['add_time']=time();
        $data['edit_time']=time();
        
        if( $this->add($data)){
            return $this->get($chat_id);
        }else{
            return false;
        }
    }
    
    public function get($chat_id){
        $User=D('User');
        $Admin=D('Admin');
        
        $where=[];
        $where['chat_id']=$chat_id;
        $data=$this->where($where)->find();
        
        
        if($data['role']==1){
            //客服
            $admin_id=$data['admin_id'];
            $where=[];
            $where['admin_id']=$admin_id;
            $admin=$Admin->field('admin_id,admin_name,admin_head_img')->where($where)->find();
            
            $user=[];
            $user['id']=$admin['admin_id'];
            $user['name']=$admin['admin_name'];
            $user['img']=$admin['admin_head_img'];
            
        }
        
        if($data['role']==2){
            //客户
            $user_id=$data['user_id'];
            $where=[];
            $where['user_id']=$user_id;
            $user1=$User->where($where)->find();
            
            $user=[];
            $user['id']=$user1['user_id'];
            $user['name']=$user1['user_name'];
            $user['img']=$user1['user_head'];
        }
        
        $data['user']=$user;
        return $data;
    }
    
    public function getData($id){
        
        $where=[];
        $where['chat_id']=['in',$id];
        $list=$this->where($where)->select();
        return $this->bulider($list);
        
    }
    
    public function update($data){
        
        $localTotal=$data['localTotal'];
        $where=[];
        $where['user_id']=session('user_id');
        $where['admin_id']=$data['admin_id'];
        
        $total=$this->where($where)->count();
        $res['total']=$total;
        $res['res']=0;
        
        
        if($total>$localTotal){
            // 有更新
            // 公式：0到最新总数-本地总数
            
            $page   =  1;
            $limit  =  $total-$localTotal ;
            
            $list  =  $this
            ->order('add_time desc')
            ->where($where)
            ->limit(($page-1)*$limit,$limit)
            ->select();
            
            $res['msg']=$this->bulider($list);
            $res['res']=count($list);
        }
        
        return $res;
        
        
    }
    
    public function history(){}
    
    public function del(){
        
    }
    
    public function bulider($list){
        $User=D('User');
        $Admin=D('Admin');
        
        foreach ($list as $k => $v) {
            // ===================================================================================
            // 用户数据
            if($v['role']==1){
                //客服
                $admin_id=$v['admin_id'];
                $where=[];
                $where['admin_id']=$admin_id;
                $admin=$Admin->field('admin_id,admin_name,admin_head_img')->where($where)->find();
                
                $user=[];
                $user['id']=$admin['admin_id'];
                $user['name']=$admin['admin_name'];
                $user['img']=$admin['admin_head_img'];
                
            }
            
            if($v['role']==2){
                //客户
                $user_id=$v['user_id'];
                $where=[];
                $where['user_id']=$user_id;
                $user1=$User->where($where)->find();
                
                $user=[];
                $user['id']=$user1['user_id'];
                $user['name']=$user1['user_name'];
                $user['img']=$user1['user_head'];
            }
            
            $v['user']=$user;
            
            
            
            
            $list[$k]=$v;
        }
        
        $list=toTime($list);
        
        return $list;
        
    }
    
}