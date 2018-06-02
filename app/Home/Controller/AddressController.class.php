<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日09:59:42
* 最新修改时间：2018年3月2日09:59:42
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####收货地址控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class AddressController extends CommonController{
    
    public function get(){
        
        $model=M('address');
        $address_id=I('address_id');
        $where=[];
        $where['address_id']=$address_id;
        $where['user_id']=session('user_id');
        
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
    
    public function add(){
        $model=M('address');
        $add=I('add','',false);
        if(!$add){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['user_id']=session('user_id');
        $add['address_id']=getMd5('address');
        
        $is_default=$add['is_default'];
        
        if($is_default){
            //是默认的
            $save['is_default']=0;
            $where=[];
            $where['user_id']=session('user_id');
            $model->where($where)->save($save);
        }
        
        $add['is_default'] = $add['is_default']==true?1:0;
            
            
            $result=$model->add($add);
            if($result){
                $res['res']=1;
                $res['msg']=$add;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function del(){
        //如果要删除的是默认地址，需要重新设置默认地址
        $address_id=I('address_id');
        $model=M('address');
        
        $where=[];
        $where['user_id']=session('user_id');
        $where['address_id']=$address_id;
        
        
        //先查找
        $result=  $model->where($where)->find();
        //判断是不是默认的
        
        
        if($result['is_default']){
            //是默认的，需要设置一个新的默认
            $where=[];
            $where['user_id']=session('user_id');
            $result=$model->where($where)->find();
            $where['address_id']=$result['address_id'];
            $save=[];
            $save['is_default']=1;
            $save['edit_time']=time();
            $model->where($where)->save($save);
        }
        
        
        $where=[];
        $where['user_id']=session('user_id');
        $where['address_id']=$address_id;
        
        $result=$model->where($where)->delete();
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        $Address=M('Address');
        $where=[];
        $where['user_id']=session('user_id');
        $result=$Address->where($where)->select();
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function save(){
        $model=M('address');
        $save=I('save','',false);
        $is_default=$save['is_default'];
        if($is_default){
            //是默认的
            $save=[];
            $save['is_default']=0;
            $where=[];
            $where['user_id']=session('user_id');
            $model->where($where)->save($save);
        }
        $save=I('save','',false);
        
        unset($save['address_id']);
        unset($save['add_time']);
        unset($save['edit_time']);
        
        $where=[];
        $where['user_id']=session('user_id');
        $where['address_id']=I('address_id');
        
        $save['is_default'] = $save['is_default'] ==true? 1 : 0 ;
            
            $result=$model->where($where)->save($save);
            
            
            if($result){
                $res['res']=$result;
                $res['msg']=$result;
                $save['edit_time']=time();
                $result=$model->where($where)->save($save);
                
        }else{
            $res['res']=-1;
            $res['msg']=$model->_sql();
        }
        echo json_encode($res);
    }
    
    
    //设置默认项
    public function setDefault(){
        $model=M('address');
        $address_id=I('address_id');
        $where=[];
        $where['user_id']=session('user_id');
        
        //先设置所有的都不是默认
        $save=[];
        $save['is_default']=0;
        $model->where($where)->save($save);
        
        //再设置默认项
        $where['address_id']=$address_id;
        $save['is_default']=1;
        $result=$model->where($where)->save($save);
        
        // 输出
        
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