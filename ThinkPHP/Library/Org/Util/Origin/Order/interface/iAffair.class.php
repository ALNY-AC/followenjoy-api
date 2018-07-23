<?php
interface iAffair {
    // ===================================================================================
    // 事务队列
    // 当一切都确定后，开始各个组件自己记录自己的数据。
    /**
    * 事务开始执行前调用。
    * 当事务开始执行前调用。
    */
    public function affairStart();
    
    /**
    * 当事务开始后调用
    */
    public function affair();
    
    /**
    * 当事务结束后调用
    */
    public function affairEnd();
    
}