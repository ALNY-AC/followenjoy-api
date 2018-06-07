<?php
namespace Admin\Model;
use Think\Model;
class TimeGoodsModel extends Model {
    
    
    public function _initialize (){}
    
    public function create($data){
        
        
        $goods_ids=$data['goods_id'];
        
        $addAll=[];
        
        foreach ($goods_ids as $k => $v) {
            
            $item['time_goods_id']=getMd5('time_goods');
            // $item['time_axis_id']='';
            $item['goods_id']=$v;
            $item['sort']=0;
            $item['is_show']=1;
            $item['start_time']=$data['start_time'];
            
            // dump(date('Y-m-d H:i:s',$data['start_time']));
            $end_time=strtotime("+1 day",$start_time);
            $data['end_time']=$end_time;
            
            
            $item['end_time']=$end_time;
            $item['add_time']=time();
            $item['edit_time']=time();
            
            $where=[];
            $where['goods_id']=$v;
            $where['start_time']=$data['start_time'];
            if(!$this->where($where)->find()){
                $addAll[]=$item;
            }
            
            
        }
        
        return $this->addAll($addAll);
        
    }
    
    public function getList($data){
        
        
        $Goods=D('Goods');
        $start_time=$data['start_time'];
        $goods_id=$this->where(['start_time'=>$start_time])->getField('goods_id',true);
        
        
        
        if($goods_id){
            $data['where']['goods_id']=['in',$goods_id];
            $list=$Goods->getList($data);
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
        
        
        $start_time=$data['time'];
        $end_time=strtotime("+1 month",$start_time);
        
        // dump(date('Y-m-d H:i:s',$start_time));
        // dump(date('Y-m-d H:i:s',$end_time));
        
        // 大于time且小于time+1月
        
        $where=[];
        $where['start_time'] = [['gt',$start_time],['lt',$end_time]];
        $list=$this->where($where)->group('start_time')->getField('start_time',true);
        
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
    
    
    
}