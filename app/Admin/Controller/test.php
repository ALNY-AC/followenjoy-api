<?php

$where=$data['where'];
$key=$data['key'];
if($key){
    
    $keys=$key;
    //先根据空格分割为数组
    $keys = explode(" ", $keys);
    $keys = array_filter($keys);  // 删除空元素
    
    foreach ($keys as $k => $v) {
        $keys[$k]='%'.$v.'%';
    }
    $group=$data['group'];
    $where[$group]=['like',$keys,'OR'];
    
}
$data['where']=$where;
?>

  <span class="float-right">
<el-input class="input-with-select" placeholder="请输入要搜索的内容" @keyup.enter.native="update()" size="mini" v-model="key">
<el-select v-model="group" slot="prepend" placeholder="请选择">
<el-option value="order_id" label="订单号"></el-option>
<el-option value="user_id" label="用户id"></el-option>
</el-select>
<template slot="append">
<el-button @click="update()">搜索</el-button>
</template>
</el-input>
</span>


  <script>
    {
      key: '',
      group: '',
      key: this.key,
      group: this.group,
    }
  </script>