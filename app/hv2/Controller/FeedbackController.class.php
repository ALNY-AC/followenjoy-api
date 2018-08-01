<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月5日11:09:123
* 最新修改时间：2018年3月5日11:09:123
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

namespace Home\Controller;
use Think\Controller;
class FeedbackController extends CommonController{
    
    
    public function add(){
        
        // 0 : bug 反馈
        // 1 : 意见反馈
        // 2 : UI问题
        // 3 ：其他
        
        $add=[];
        
        $add=I('add','',false);
        
        $add['feedback_id']=date('YmdHis',time()).rand(1000,9999);
        $add['user_id']=session('user_id');
        $add['add_time']=time();
        $add['edit_time']=time();
        
        
        $add['feedback_info']=trim($add['feedback_info']);
        
        $model=M('feedback');
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