<?php
namespace Home\Model;
use Think\Model;
class TimeGoodsModel extends Model {
    
    public function _initialize (){}
    
    
    public function getList($data){
        
        $Goods=D('Goods');
        $start_time=$data['start_time'];
        $where=[];
        $where['start_time']=$start_time;
        $where['is_show']=1;
        $goods_id=$this->where($where)->getField('goods_id',true);
        
        if($goods_id){
            $where['goods_id']=['in',$goods_id];
            $data=[];
            $data['limit']=200;
            $list=$Goods->getList($data,  $where);
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
    // 取指定日期的和指定时间的
    public function getPlus($data){
        
        $page   =   $data['page']?$data['page']:1;
        $page_size  =   $data['page_size']?$data['page_size']:5;
        $where  =   $data['where']?$data['where']:[];
        $field  =   $data['field']?$data['field']:[];
        
        
        $Goods=D('Goods');
        $start_time=$data['start_time'];
        $where=[];
        $where['start_time']=$start_time;
        $where['is_show']=1;
        $goods_id=$this
        ->where($where)
        ->limit(($page-1)*$page_size,$page_size)
        ->getField('goods_id',true);
        
        
        if($goods_id){
            $where=[];
            $where['goods_id']=['in',$goods_id];
            $list=$Goods->getList([],  $where);
            
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
    // 取明天的时刻表
    public function getTomorrow($data){
        
        $gt_time=$data['gt_time'];//客户端出来的要大于哪一天的时间戳
        
        $end_time=strtotime("+1 day",$gt_time);//后台计算24小时后的时间戳，用户限制，一般只取当天的时间
        
        // dump($gt_time);
        // dump($end_time);
        // dump(date('Y-m-d H:i:s',$gt_time));
        // dump(date('Y-m-d H:i:s',$end_time));
        
        $where=[];
        
        $where['start_time'] = [['EGT',$gt_time],['ELT',$end_time]];
        $list=$this->where($where)->group('start_time')->getField('start_time',true);
        
        
        foreach ($list as $k => $v) {
            $item=[];
            $item['time_label']=date('H:i',$v);
            $item['time_value']=$v;
            // dump(date('Y-m-d H:i:s',strtotime("+1 day",$v)));
            $item['goods_count']=$this->where(['start_time'=>$v])->count()+0;
            
            $list[$k]=$item;
        }
        
        // dump($list);
        return $list? $list:[];
    }
    // 取昨天
    public function getTesterday(){
        
        $toTime=time();//当前时间
        
        $start_time=strtotime("-24 hours",$toTime);//24小时前的
        $start_time=$this->getWholeTime($start_time);//取整点
        
        $end_time=$this->getTesterdayTime();//昨天晚上23点的
        
        // dump(date('Y-m-d H:i:s',$toTime));
        // dump(date('Y-m-d H:i:s',$start_time));
        // dump(date('Y-m-d H:i:s',$end_time));
        
        // ===================================================================================
        //
        $Goods=D('Goods');
        $where=[];
        $where['is_show']=1;
        $where['start_time'] = [['EGT',$start_time],['ELT',$end_time]];
        $goods_id=$this->where($where)->getField('goods_id',true);
        
        if($goods_id){
            $where['goods_id']=['in',$goods_id];
            $data=[];
            $data['limit']=200;
            $list=$Goods->getList($data,  $where);
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
    
    
    // 取整点
    private function getWholeTime($time){
        
        $time = mktime(date('h',$time), 0, 0, date("n", $time), date("j", $time), date("Y", $time));
        return    $time;
        
    }
    
    
    private function getTesterdayTime(){
        // ===================================================================================
        // 取昨天晚上23点
        $secondsOneDay = 60 * 60 * 24;
        $now = time();
        $yesterday = $now - $secondsOneDay;
        $end_time = mktime(23, 0, 0, date("n", $yesterday), date("j", $yesterday), date("Y", $yesterday));
        return    $end_time;
    }
    
    
    
}