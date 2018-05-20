<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日08:29:54
* 最新修改时间：2018年4月9日08:29:54
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####帮助控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class HelpController extends CommonController{
    
    
    public function add(){
        
        $Help=D('Help');
        $add=I('add','',false);
        $add['help_id']=getMd5('help');
        $add['add_time']=time();
        $add['edit_time']=time();
        $result=$Help->add($add);
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
        
        $Help=D('Help');
        
        $save=I('save','',false);
        $where=I('where');
        
        unset($save['help_id']);
        unset($save['add_time']);
        
        $save['edit_time']=time();
        
        $result=$Help->where($where)->save($save);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    
    public function getList(){
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where');
        
        $Help=D('Help');
        
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
        
        
        $result=$Help->where($where)->limit(($page-1)*$limit,$limit)->select();
        $result=toTime($result);
        
        $res['count']=$Help->count()+0;
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        
        $Help=D('Help');
        $where=I('where');
        $result=$Help->where($where)->find();
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function del(){
        
        $help_id=I('help_id');
        $Help=D('Help');
        $where=[];
        $where['help_id']=['in',$help_id];
        $result=$Help->where($where)->delete();
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