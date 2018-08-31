<?php
namespace Home\Model;
use Think\Model;
class PaperModel extends Model {
    
    
    public function getList($where=[]){
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $res['count']=$this
        
        ->where($where)
        ->count()+0;
        
        $field=[
        
        ];
        
        $papers=$this
        ->cache(true,3600)
        // ->field($field)
        ->order('sort asc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        $papers=to_format_date($papers,'add_time');
        
        return $papers;
        
    }
    
    
    
}