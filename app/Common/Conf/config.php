<?php
return array(
'TMPL_PARSE_STRING' => array(

'__VENDOR__' => __ROOT__ . '/Public/vendor', // 配置自定义资源文件夹：第三方库
'__ADIST__' => __ROOT__ . '/Public/Admin/dist', // 配置自定义资源文件夹：admin
'__HDIST__' => __ROOT__ . '/Public/Home/dist', // 配置自定义资源文件夹：home
'__ASSETS__' => __ROOT__ . '/Public/Assets', // 配置自定义资源文件夹：home
'__DIST__' => __ROOT__ . '/Public/dist', // 配置自定义资源文件夹：home
'__PUBLIC__' => __ROOT__ . '/Public', // 配置自定义资源文件夹

),

/* 数据库设置 */

// ===================================================================================
// 本地
'DB_TYPE' => 'mysql', // 数据库类型
'DB_HOST' => '127.0.0.1', // 服务器地址
'DB_NAME' => 'cosmetics', // 数据库名
'DB_USER' => 'root', // 用户名
'DB_PWD' => 'mysqlyh12138..', // 密码
'DB_PORT' => '3306', // 端口
'DB_PREFIX' => 'c_', // 数据库表前缀
'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),

// ===================================================================================
// 正式

// 'DB_TYPE' => 'mysql', // 数据库类型
// 'DB_HOST' => 'hdm106787188.my3w.com', // 服务器地址
// 'DB_NAME' => 'hdm106787188_db', // 数据库名
// 'DB_USER' => 'hdm106787188', // 用户名
// 'DB_PWD' => 'zxy158362541206', // 密码
// 'DB_PORT' => '3306', // 端口
// 'DB_PREFIX' => 'c_', // 数据库表前缀
// 'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),

// ===================================================================================
// 测试
// 'DB_TYPE' => 'mysql', // 数据库类型
// 'DB_HOST' => '101.132.182.102', // 服务器地址
// 'DB_NAME' => 'followenjoy', // 数据库名
// 'DB_USER' => 'root', // 用户名
// 'DB_PWD' => 'followenjoy8787', // 密码
// 'DB_PORT' => '3306', // 端口
// 'DB_PREFIX' => 'c_', // 数据库表前缀
// 'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),


"weixin"=>array(
"APPID"=>"wx8c3b0269e9e2c724",
"MCHID"=>"1504196381",
"KEY"=>"8312162ee470f489870f1fd35288a946",
// bcc5e9cfa42e8be37da2a6812f992bad
),


);