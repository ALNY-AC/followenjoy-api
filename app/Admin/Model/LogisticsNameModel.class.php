<?php
namespace Admin\Model;
use Think\Model;
class LogisticsNameModel extends Model {
    
    public function getList(){
        return $this->select();
    }
    
}