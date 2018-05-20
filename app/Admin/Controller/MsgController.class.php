<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月4日16:12:20
* 最新修改时间：2018年4月4日16:12:20
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####消息控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class MsgController extends CommonController{
    
    public function getList(){
        $Msg=M('Msg');
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        $data=I();
        
        $key=$data['key'];
        if($key){
            
            $keys=$key;
            //先根据空格分割为数组
            $keys = explode(" ", $keys);
            $keys = array_filter($keys);  // 删除空元素
            
            foreach ($keys as $k => $v) {
                $keys[$k]='%'.$v.'%';
            }
            $group=$data['group'];
            $where[$group]=['like',$keys,'OR'];
            
        }
        
        
        $res['count']=$Msg->where($where)->count()+0;
        $msgs=$Msg
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $msgs=toTime($msgs);
        
        if($msgs){
            $res['res']=count($msgs);
            $res['msg']=$msgs;
        }else{
            $res['res']=-1;
            $res['msg']=$msgs;
        }
        echo json_encode($res);
        
    }
    
    
    public function get(){
        
        $msg_id=I('msg_id');
        $where=[];
        $where['msg_id']=$msg_id;
        $Msg=D('Msg');
        $result=$Msg->where($where)->find();
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function creat(){
        $data=I('data','',false);
        $Msg=D('Msg');
        $data['msg_id']=getMd5('msg');
        $data['add_time']=time();
        $data['edit_time']=time();
        $result=  $Msg->add($data);
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function save(){
        
        $msg_id=I('msg_id');
        $where=[];
        $where['msg_id']=$msg_id;
        
        $data=I('data','',false);
        $Msg=D('Msg');
        
        unset($data['msg_id']);
        unset($data['add_time']);
        $data['edit_time']=time();
        
        $result=$Msg->where($where)->save($data);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    public function del(){
        
        $msg_id=I('msg_id');
        $Msg=D('Msg');
        $where=[];
        $where['msg_id']=['in',$msg_id];
        $result=$Msg->where($where)->delete();
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
}