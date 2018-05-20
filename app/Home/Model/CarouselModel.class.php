<?php
namespace Home\Model;
use Think\Model;
class CarouselModel extends Model {
    
    
    public function _initialize (){}
    
    public function getList($where){
        $carouselList  =  $this
        ->order('sort asc,add_time desc')
        ->where($where)
        ->select();
        return $carouselList;
    }
    
    
}