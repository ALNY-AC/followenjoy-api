<?php
// +----------------------------------------------------------------------

// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//跨域
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
$allow_origin=[
// 本地测试
'http://192.168.1.251:8081',
'http://192.168.1.251:8080',
'http://192.168.1.101:8081',
'http://192.168.1.101:8080',

'http://192.168.0.102:8081',
'http://192.168.0.102:8080',

'http://cosmetics.com',

// 测试端
'http://test.q.followenjoy.cn',
'http://test.admin.followenjoy.cn',
'http://test.server.followenjoy.cn',

// 正式运营
"http://cuelyine.cn",
'http://followenjoy.cn',

'http://server.followenjoy.cn',
'http://server.followenjoy.com',
'http://server2.followenjoy.com',

'http://admin.followenjoy.cn',
'http://admin.followenjoy.com',

'http://q.followenjoy.com',
'http://q.followenjoy.cn',


];
$is=in_array($origin, $allow_origin);
if(in_array($origin, $allow_origin)){
    header('Access-Control-Allow-Origin:'.$origin);
    header('Access-Control-Allow-Credentials:true');
}else{
    header('Access-Control-Allow-Origin:*');
}
header("Content-type: text/html; charset=utf-8");

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);
// 定义应用目录
define('APP_PATH','./app/');

//定义项目名
define('APP_NAME','webpack 项目模板');

//自定义常量
// 全局key
define('__KEY__', 'c12138..');
//定义工作路径
define('WORKING_PATH', str_replace('\\', '/', __DIR__));
//定义根上传路径
define('UPLOAD_ROOT_PATH', '/Public/Upload/');
//定义上传的根目录——用户
define('__UPLOAD__USER__', '/Public/Upload/user/');
//定义上传的根目录——管理
define('__UPLOAD__ADMIN__', '/Public/Upload/admin/');
//定义上传的根目录—home
define('__UPLOAD__HOME__', '/Public/Upload/home/');

// 是否是测试环境API
define('IS_DEBUG', false);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单