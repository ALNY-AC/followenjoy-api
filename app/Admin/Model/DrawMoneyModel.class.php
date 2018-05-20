<?php
namespace Admin\Model;
use Think\Model;
class DrawMoneyModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($data){
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $list  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $list=toTime($list);
        
        return $list;
        
    }
    
    public function get($draw_money_id){
        $where=[];
        $where['draw_money_id']=$draw_money_id;
        $result=$this->where($where)->find();
        $result=toTime([$result])[0];
        return $result;
    }
    
    public function saveData($draw_money_id,$save){
        $where=[];
        $where['draw_money_id']=$draw_money_id;
        $save['edit_time']=time();
        unset($save['state']);
        return $this->where($where)->save($save);
    }
    
    public function del($ids){
        $where=[];
        $where['draw_money_id']=['in',$ids];
        return $this->where($where)->delete();
    }
    
    public function getAll($data){
        $where=$data['where'];
        return $this->order('add_ime desc')->where($where)->select();
    }
    
    public function setState($draw_money_id,$state,$reason){
        // ===================================================================================
        // 创建模型
        $User=D('User');
        
        // ===================================================================================
        //
        
        $where=[];
        $where['draw_money_id']=$draw_money_id;
        
        $save=[];
        $save['state']=$state;
        $save['reason']=$reason;
        $save['edit_time']=time();
        
        // ===================================================================================
        // 判断，只能是待审核状态进行修改。
        $drawMoney=$this->where($where)->find();
        
        if($drawMoney['state']=='1'){
            //待审核，可以进行操作
            //减少用户的钱
            $this->where($where)->save($save);
            
            $where=[];
            $where['user_id']=session('user_id');
            $money=$drawMoney['money'];
            if($state=='2'){
                //状态2就是审核通过，所以要减去用户的钱
                $User->where($where)->setDec('user_money',$money);
            }
            
            return true;
        }else{
            return false;
        }
        
    }
    
}