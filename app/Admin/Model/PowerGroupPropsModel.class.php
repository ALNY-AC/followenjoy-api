<?php
namespace Admin\Model;
use Think\Model;
class PowerGroupPropsModel extends Model {
    
    public function _initialize (){}
    
    public function saveData($power_group_id,$ids){
        $where=[];
        $where['power_group_id']=$power_group_id;
        
        $this->where($where)->delete();//先删除
        
        $adds=[];
        foreach ($ids as $k => $v) {
            $item['power_group_props_id']=getMd5($power_group_id.'power_group_props',$v);
            $item['power_group_id']=$power_group_id;
            $item['power_num_id']=$v;
            $item['add_time']=time();
            $item['edit_time']=time();
            $adds[]=$item;
        }
        
        return $this->addAll($adds);
    }
    
    
    
}