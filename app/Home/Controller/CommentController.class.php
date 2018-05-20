<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月6日14:15:18
* 最新修改时间：2018年3月6日14:15:18
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####评论控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class CommentController extends CommonController{
    
    public function add(){
        $model=M('comment');
        $add=I('add');
        if(!$add){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $add['user_id']=session('user_id');
        $add['comment_id']=getMd5('comment');
        $add['add_time']=time();
        $add['edit_time']=time();
        
        $result=$model->add($add);
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