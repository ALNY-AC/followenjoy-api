<?php
namespace Home\Model;
use Think\Model;
class TimeGoodsModel extends Model {
    
    public function _initialize (){}
    
    
    public function getList($data){
        
        
        $Goods=D('Goods');
        $start_time=$data['start_time'];
        $goods_id=$this->where(['start_time'=>$start_time])->getField('goods_id',true);
        
        if($goods_id){
            $data['where']['goods_id']=['in',$goods_id];
            $list=$Goods->getList($data,  $data['where']);
        }else{
            $list=[];
        }
        
        foreach ($list as $k => $v) {
            
            $where=[];
            $where['goods_id']=$v['goods_id'];
            $where['start_time']=$start_time;
            $d=$this->where($where)->find();
            
            $is_show=$d['is_show'];
            $start_time=$d['start_time'];
            $end_time=$d['end_time'];
            $sort=$d['sort'];
            
            $v['start_time']=$start_time;
            $v['end_time']=$end_time;
            $v['sort']=$sort;
            
            $v['is_show']=$is_show+0;
            $list[$k]=$v;
        }
        
        return $list;
    }
    
    public function getAll($data){
        
        $Goods=D('Goods');
        $time_id=$data['time_id'];
        
        $goods_id=$this->where(['time_id'=>$time_id])->getField('goods_id',true);
        
        
        
        if($goods_id){
            $data['where']['goods_id']=$goods_id;
            $list=$Goods->getAll($goods_id);
        }else{
            $list=[];
        }
        return $list;
    }
    
    public function del($goods_id,$start_time){
        $where=[];
        $where['goods_id']=['in',getIds($goods_id)];
        $where['start_time']=$start_time;
        return $this->where($where)->delete();
    }
    
    
    public function getData($data){
        
        
        $start_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end_time=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        
        // dump($start_time);
        // dump($end_time);
        
        // dump(date('Y-m-d H:i:s',$start_time));
        // dump(date('Y-m-d H:i:s',$end_time));
        
        $where=[];
        
        $where['start_time'] = [['EGT',$start_time],['ELT',$end_time]];
        $list=$this->where($where)->group('start_time')->getField('start_time',true);
        
        // dump($list);
        
        foreach ($list as $k => $v) {
            $item=[];
            $item['time_label']=date('H:i',$v);
            $item['time_value']=$v;
            
            // dump(date('Y-m-d H:i:s',strtotime("+1 day",$v)));
            
            $item['goods_count']=$this->where(['start_time'=>$v])->count();
            
            
            $list[$k]=$item;
        }
        // dump($list);
        return $list? $list:[];
    }
    
    public function saveData($goods_id,$start_time,$data){
        $where=[];
        $where['start_time']=$start_time;
        $where['goods_id']=['in',getIds($goods_id)];
        
        unset($data['add_time']);
        $data['edit_time']=time();
        $this->where($where)->save($data);
    }
    
    public function get($data){
        
        $goods_id=$data['goods_id'];
        
        // 取正在进行时的数据
        $toTime=time();//当前时间
        // 大于开始时间，且小于结束时间
        
        $where=[];
        $where['goods_id'] = $goods_id;
        
        $start_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end_time=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        
        $where['start_time'] = [['EGT',$start_time],['ELT',$end_time]];
        
        
        $timeData=$this->where($where)->order('start_time asc')->find();
        
        return $timeData;
        
    }
    
}