<?php
namespace Admin\Model;
use Think\Model;
class TimeModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($data){
        
        $times=$this
        ->order('start_time asc')
        ->select();
        
        $Goods=D('goods');
        for ($i=0; $i < count($times); $i++) {
            $goods_id=$times[$i]['goods_id'];
            $goods=$Goods->get($goods_id);
            $times[$i]['goods']=$goods;
        }
        
        
        $tree=[];
        
        for ($i=0; $i <count($times) ; $i++) {
            
            $time=$times[$i]['start_time'];
            $name=date('H:i',$time);
            
            $tree[$name]['name']=$name;
            $tree[$name]['start_time']=$time;
            $tree[$name]['goods'][]=$times[$i]['goods'];
            
            
            if(time()-3600>$time){
                //判断是否过期 overdue
                $tree[$name]['isOverdue']=true;
            }else{
                $tree[$name]['isOverdue']=false;
            }
            
        }
        
        
        return $tree;
    }
    
    
    
}