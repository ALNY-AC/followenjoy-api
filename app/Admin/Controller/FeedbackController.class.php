<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月6日10:14:35
* 最新修改时间：2018年3月6日10:14:35
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户反馈控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class FeedbackController extends CommonController{
    
    //获得列表
    public function getList(){
        
        
        // 0 : bug 反馈
        // 1 : 意见反馈
        // 2 : UI问题
        // 3 ：其他
        
        $model=M('feedback');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $res['count']=$model->where($where)->count()+0;
        
        $result=$model
        ->table('c_feedback as t1,c_user as t2')
        ->field('t1.*,t2.user_name,t2.user_id')
        ->where($where)
        ->where('t1.user_id = t2.user_id')
        ->order('add_time desc,feedback_type asc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        // =========判断=========
        if($result){
            $result=toTime($result);
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    public function get(){
        $model=M('feedback');
        $where=I('where');
        $result=$model->where($where)->find();
        
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
        
        $model=M('feedback');
        $where=I('where');
        $save=I('save');
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
}