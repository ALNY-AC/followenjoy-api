<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月7日11:09:10
* 最新修改时间：2018年3月7日11:09:10
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分销商控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class ForkController extends CommonController{
    
    
    public function getList(){
        $model=M('user');
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        
        $where=[];
        $where['user_type']=1;
        $res['count']=$model
        ->where($where)
        ->order('add_time desc')
        ->count()+0;
        
        $where=[];
        $result= $model
        ->where("c_user.user_type = 1 ")
        ->field('c_user.*,c_star.star_name,c_star.star_id')
        ->join('c_star ON c_user.star_id = c_star.star_id','LEFT')
        ->order('c_user.add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        // dump($result);
        // die;
        
        // =========判断=========
        if($result){
            
            
            
            // 还得找上级的
            $model=M('user_super');
            
            
            foreach ($result as $key => $value) {
                
                $user_id=$value['user_id'];
                $super_id=null;
                $where=[];
                $where['t1.user_id']=$user_id;
                $user_super=$model
                ->table('c_user_super as t1,c_user as t2,c_star as t3')
                ->where($where)
                ->where('t1.super_id = t2.user_id AND t2.star_id = t3.star_id ')
                ->find();
                
                if($user_super){
                    
                    $result[$key]['super_name']=$user_super['user_name'];
                    $result[$key]['super_id']=$user_super['super_id'];
                    $result[$key]['super_star_id']=$user_super['star_id'];
                    $result[$key]['super_star_name']=$user_super['star_name'];
                    // dump($result[$key]);
                    
                }
                
            }
            
            $result=toTime($result);
            
            $res['res']=1;
            $res['msg']=$result;
            $res['where']=$where;
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
    }
    
    
    //获得所有是分销商的用户
    public function getForkList(){
        
        $model=M('user');
        $result=$model
        ->table('c_user as t1,c_star as t2')
        ->field('t1.*,t2.*')
        ->where('t1.user_type = 1 AND t1.star_id = t2.star_id')
        ->order('t1.add_time desc')
        ->select();
        
        // =========判断=========
        if($result){
            
            $result=toTime($result);
            $res['res']=count($result);
            $res['msg']=$result;
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    
    
    public function setSuper(){
        
        $add=I('add');
        
        if(!$add){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $model=M('user_super');
        
        
        
        $where['user_id']= $add['user_id'];
        
        //先删除
        $result=$model->where($where)->delete();
        
        //后添加
        
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