<?php
/**批量转换时间 */
function toTime($arr,$code='Y-m-d H:i:s',$field=[]){
    
    
    foreach ($arr as $key => $value) {
        
        if(!empty($value['add_time'])){
            $arr[$key]['add_time']=date($code,$value['add_time']);
        }
        if(!empty($value['edit_time'])){
            $arr[$key]['edit_time']=date($code,$value['edit_time']);
        }
        
        for ($i=0; $i <count($field) ; $i++) {
            $arr[$key][$field[$i]]=date($code,$value[$field[$i]]);
        }
        
    }
    
    return $arr;
    
}
function toTime2($arr,$code='Y-m-d H:i:s',$field=[]){
    
    foreach ($arr as $key => $value) {
        for ($i=0; $i <count($field) ; $i++) {
            $arr[$key][$field[$i]]=date($code,$value[$field[$i]]);
        }
        
    }
    return $arr;
    
}


function to_format_date($arr,$field){
    foreach ($arr as $key => $value) {
        if(!empty($value[$field])){
            $arr[$key][$field]=format_date($value[$field]);
        }
    }
    return $arr;
}

function format_date($time){
    $t=time()-$time;
    $f=array(
    '31536000'=>'年',
    '2592000'=>'个月',
    '604800'=>'星期',
    '86400'=>'天',
    '3600'=>'小时',
    '60'=>'分钟',
    '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
    return "0秒前";
}

function toHtml($arr,$field){
    
    
    foreach ($arr as $key => $value) {
        
        if(!empty($value[$field])){
            $arr[$key][$field]=htmlspecialchars_decode($value[$field]);
        }
        
    }
    
    return $arr;
    
}
function html($arr){
    return htmlspecialchars_decode($arr);
}
/**强验证是否正确 */
function check($var){
    
    return isset($var) && !empty($var) ? true:false;
    
}
/**判断验证码是否正确 */
function isCode($code){
    //验证 验证码
    //校验验证码（不需要传参）
    $verify = new \Think\Verify();
    //验证
    return $verify -> check($code);
    
}
/**获得验证码 */
function getCode($cfg){
    
    if(!$cfg){
        //验证码配置
        $cfg = array(
        'fontSize' => 12, // 验证码字体大小(px)
        'useCurve' => false, // 是否画混淆曲线
        'useNoise' => false,
        // 是否添加杂点
        'length' => 4, // 验证码位数
        'fontttf' => '4.ttf', // 验证码字体，不设置随机获取
        );
    }
    
    //实例化验证码类
    $verify = new \Think\Verify($cfg);
    //输出验证码
    ob_clean();
    $verify -> entry();
    
}
/**验证用户名和密码是否匹配 */
function login($form,$id,$pwd,$isMd5=true){
    
    if($isMd5){
        $pwd=md5($pwd.__KEY__);
    }
    
    $model=M($form);
    $where=[];
    $where[$form.'_id']=$id;
    
    $result=$model->where($where)->find();
    if($result[$form.'_pwd']===$pwd){
        //验证成功
        return $result;
    }else{
        return false;
    }
    
}


/**
* 创建 token
* * @param String login_id 用于混入md5加密中的 用户的登录的id
* * @param String table 要储存token的表格，默认为 token
*/
function createToken($login_id,$table="token"){
    
    if(!check($login_id)){
        //如果 user_id 不存在，也就不能生成token
        return false;
    }
    
    //创建token
    $token=md5($login_id.rand().time().__KEY__);
    
    //创建要保存的数据
    $add['token']=$token;
    $add['login_id']=$login_id;
    $add['edit_time']=time();
    
    //创建模型
    $model=M($table);
    //添加数据，如果存在则覆盖
    $result=$model->add($add,null,true);
    
    if($result){
        //创建成功
        return $token;
    }else{
        //创建失败
        return false;
    }
    
}


/**
* 查询数据
*/
function getList(){
    
    //初始化
    $data=[];
    $res=[];
    $where=[];
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    $model=M($table);
    //获得条件查询
    $where=I('where')?I('where'):[];
    //初始化 end
    
    //分页记录
    //当前页数
    $page=I('page')?I('page'):0;
    //一次查询条数
    $limit=I('limit')?I('limit'):10;
    //分页记录 end
    
    //条件查询
    $key=I('key');
    $key_where= I('key_where');
    
    if(check($key)){
        //如果存在就查询
        $where[$key_where] = array(
        'like',
        "%".$key."%",
        'OR'
        );
    }
    
    //条件查询 end
    
    //生成数据
    $data=$model
    ->field('t1.*,t2.user_id,t2.user_name')
    ->table('ao_feedback as t1,ao_user as t2')
    ->order('t1.add_time asc')
    ->where('t1.user_id = t2.user_id')
    ->where($where)
    ->select();
    
    //总条数
    $res['count']=count($data);
    //取指定条数
    //索引位置=当前页数-1*每页展示量
    
    if(check($page)){
        //如果有分页数据，才分页
        $data=array_slice($data ,($page-1)*$limit,$limit);
    }
    //转换时间戳
    $data=toTime($data);
    //取得成功状态S
    $res['res']=1;
    //数据
    $res['msg']=$data;
    return $res;
    
}
/**
* 初始化获得列表
*/
function initGetList(){
    $table=strtolower(I('table'));
    $model=M($table);
    $where=I('where')?I('where'):[];
    $key=I('key');
    $key_where= I('key_where');
    if(check($key)){
        //如果存在就查询
        $where[$key_where] = array(
        'like',
        "%".$key."%",
        'OR'
        );
    }
    
    $conf=[];
    $conf['page']=I('page')?I('page'):0;
    $conf['limit']=I('limit')?I('limit'):10;
    $conf['where']=$where;
    $conf['model']=$model;
    
    return $conf;
    
}
/**
* 分页处理
*/
function getPageList($conf,$data){
    
    if(check($conf['page'])){
        //索引位置=当前页数-1*每页展示量
        //如果有分页数据，才分页
        $data=array_slice($data ,($conf['page']-1)*$conf['limit'],$conf['limit']);
    }
    return $data;
    
}
/**
* 保存
*/
function save(){
    
    //获取要保存的数据
    $save=I('save');
    unset($save['add_time']);
    
    
    if($save['id']){
        
        $id=$save['id'];
        unset($save['id']);
        
    }
    
    
    $save['edit_time']=time();
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    //获得条件查询
    $where=I('where')?I('where'):[];
    //设置基本插叙条件为此条数据的id
    
    if($id!==null){
        $where['id']=$id;
    }else{
        
        if(I('id')){
            //如果有id，就使用id的，否则就使用上传的条件
            $where[$table.'_id']=I('id');
        }
        
    }
    
    
    
    
    //创建模型
    $model=M($table);
    $result=$model->where($where)->save($save);
    
    
    $res['sql']=$model->_sql();
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    //=========判断end=========
    
    return $res;
}
/**
* 删除单个
* $isRecycle 是否设置回收状态，默认是false，也就是真的直接删除，如果为true，并不会被真的删掉，而是设置某个字段
*/

function del($isRecycle=false,$field ,$val,$whereData){
    
    
    
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    //获得条件查询
    $where=I('where')?I('where'):[];
    if($whereData){
        $where+=$whereData;
    }
    //设置基本插叙条件为此条数据的id
    if(I('id')){
        //如果有id，就使用id的，否则就使用上传的条件
        $where[$table.'_id']=I('id');
    }
    //创建模型
    $model=M($table);
    
    if($isRecycle){
        //放在回收站里
        $save[$field]=$val;
        $result=$model->where($where)->save($save);
        
    }else{
        //真的删除
        $result=$model->where($where)->delete();
        
    }
    
    
    
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    //=========判断end=========
    return $res;
    
}
/**
* 批量删除
*/
function dels($isRecycle=false,$field ,$val){
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    $model=M($table);
    //获得条件查询
    
    $where=I('where')?I('where'):[];
    
    $where[$table.'_id']=array('in',I('ids'));
    
    if($isRecycle){
        //放在回收站里
        $save[$field]=$val;
        $result=$model->where($where)->save($save);
        
    }else{
        //真的删除
        $result=$model->where($where)->delete();
    }
    
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    
    //=========判断end=========
    return $res;
}
/**
* 添加
*/
function add($id=false,$idType=false,$addData){
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    
    $model=M($table);
    
    if(I('isDelAll')===true){
        //清空后
    }
    
    //获得添加数据
    $add=I('add');
    if($addData){
        // $add =array_merge($add,$addData);
        $add+=$addData;
    }
    if(!$idType){
        $idType=I('idType');
    }
    
    if($id===false || $id===null){
        
        if($idType=='auto'){
            
        }
        if($idType=='md5'){
            $add[$table.'_id']=md5($table.time().rand().__key__.rand(0,9999));
        }
        
    }else{
        //如果是指定的id
        $add[$table.'_id']=$id;
    }
    
    
    $add['add_time']=time();
    $add['edit_time']=time();
    //添加
    $result=$model->add($add);
    
    // $res['sql']=$model->_sql();
    
    if(I('returnData')){
        $where=[];
        $where[$table.'_id']=$id;
        $field=I('field')?I('field'):[];
        $res['msg']=$model->where($where)->field($field)->find();
    }
    
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    //=========判断end=========
    return $res;
}
/**
* 验证用户是否登录
*/
function isUserLogin($table='user'){
    
    //接收登录参数
    $login_id=I($table."_id",false);
    $token=I('token',false);
    
    if(!$login_id || !$token){
        //没有参数
        return -992;
    }
    
    
    $where['login_id']=$login_id;
    $where['token']=$token;
    $Token=M('token');
    $result=$Token->where($where)->find();
    
    // dump($where);
    // die;
    
    if($result){
        //账户正确 , token存在
        //验证token的时间过期没有
        $tokenTime=$result['edit_time'];
        $toTome=time();
        $end_time=2592000;
        if(($tokenTime+$end_time)>$toTome){
            
            //未到期
            //如果 + $end_time 大于现在的时间，就是没过期
            
            //没过期就取出用户的数据
            $User=M($table);
            $where=[];
            $where[$table.'_id']=$login_id;
            $userInfo=$User->where($where)->find();
            return $userInfo;
            
        }else{
            //如果 + $end_time 秒小于或者等于现在的时间，就是过期了
            //到期了
            //删除令牌操作
            $where=[];
            $where['login_id']=$login_id;
            session(null);
            $Token->where($where)->delete();
            return -991;
        }
    }else{
        //没有相关账户
        //未登录
        //没有令牌
        return -992;
    }
    
}
/**
* 创建目录
* set_mkdir
* =================================
* 创建日期：2017年12月16日11:31:58
* 作者：代码狮
* github：https://github.com/ALNY-AC
* 微信:AJS0314
* QQ:1173197065
* 留言：后来者想了解详细情况的请联系作者。
* =================================
*可以创建多级目录
*/
function set_mkdir($src) {
    //创建目录
    if (is_dir($src)) {
        //存在不创建
        return true;
    } else {
        //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
        $res = mkdir(iconv("UTF-8", "GBK", $src), 0777, true);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}
/**
* +-----------------------------------------------------------------------------------------
* 删除目录及目录下所有文件或删除指定文件
* +-----------------------------------------------------------------------------------------
* @param str $path   待删除目录路径
* @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
* +-----------------------------------------------------------------------------------------
* @return bool 返回删除状态
* +-----------------------------------------------------------------------------------------
*/
function delFile($path, $delDir = false) {
    if (is_array($path)) {
        foreach ($path as $subPath)
        delFile($subPath, $delDir);
    }
    if (is_dir($path)) {
        
        $handle = opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? delFile("$path/$item", $delDir) : unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir)
                return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return false;
        }
    }
    clearstatcache();
}
/**
* 让商品数量减少
* 2018年1月29日17:52:05
*/
function decGoods($orderListId){
    
    //让商品数量减少
    // $ids[0]='201801291348504982';
    $where['order_id']=array('in',$orderListId);
    $model=M('order_info');
    $result=$model->where($where)->select();
    $goodsList=[];
    foreach ($result as $key => $value) {
        $order_info=$value['order_info'];
        $order_info=html($order_info);
        $order_info=json_decode($order_info,true);
        foreach ($order_info['goods_info'] as $j => $v) {
            $goodsList[]=$v;
        }
    }
    //找商品
    $model=M('goods');
    foreach ($goodsList as $key => $value) {
        $where=[];
        $where['goods_id']=$value['goods_id'];
        $num=$value['num'];
        $model->where($where)->setDec('goods_count',3);
    }
    
}

//获得订单详细信息，返回的是json型数据
function getOrderInfo($order_id){
    $model=M('order_info');
    $where=[];
    $where['order_id']=$order_id;
    $result= $model->where($where)->find();
    $orderInfo=$result['order_info'];
    $orderInfo=html($orderInfo);
    $orderInfo=json_decode($orderInfo,true);
    return $orderInfo;
}

//获得一个订单的总价
function getOrderMoney($orderInfo){
    $money=0;
    foreach ($orderInfo['goods_info'] as $key => $value) {
        $money+=($value['money']*$value['num']);
    }
    return $money;
}
//将数组转换为json字符串
function json($arr){
    
    return serialize($arr);
}
//字符串转换为数组
function stringToArr($arr,$mz){
    
    for ($i=0; $i < count($arr); $i++) {
        
        
        for ($j=0; $j < count($map); $j++) {
            
            $arr[$i][$map[$j]]=unserialize($arr[$i][$map[$j]]);
            
            
        }
        
    }
    
    return $arr;
    
}
//遍历数组并将其中的数组转换为json
function arrToString($arr){
    
    
    foreach ($arr as $key => $value) {
        if(gettype($value)==='array'){
            $arr[$key]=serialize($value);
        }
    }
    
    return $arr;
    
}
//将字符串转换为json数组
function jsonD($arr ,$is=false){
    return json_decode($arr,$is);
}

//遍历，并将map中的字段转换为数组或json
function arrJsonD($arr,$map){
    
    // $result['img_list']=jsonD($result['img_list']);
    // $result['goods_class']=jsonD($result['goods_class']);
    // $result['spec']=jsonD($result['spec']);
    
    for ($i=0; $i < count($arr); $i++) {
        # code...
        foreach ($map as $key => $value) {
            
            $arr[$i][$key]=jsonD($arr[$i][$key],$value);
            
        }
        
    }
    
    return $arr;
}
//获得md5加密后的id
function getMd5($name="12138"){
    return md5($name.__KEY__.rand().time());
}
function getBagNum(){
    
    $where['user_id']=session('user_id');
    $bag=M('bag')->where($where)->select();
    $bag_num=0;
    for ($i=0; $i <count($bag); $i++) {
        $bag_num+=$bag[$i]['goods_count'];
    }
    $res['bag_num']=$bag_num;
    
    return $bag_num;
}


/**
* echo 的单行输出，调用一次输出一行，可自定义标签
*/
function ec($info,$tag='div',$style){
    
    $style=$style?$style:"color:#333;line-height:1;padding:5px;text-align:left";
    $log="<$tag style='$style'>$info</$tag>";
    echo $log;
}



function getGoodsSku($goods,$map=['img_list','sku','tree','class','freight']){
    
    $Goods=D('Goods');
    return $Goods->getGoodsSku($goods,$map);
    
}

function getKey(){
    
    $data=I();
    $where=$data['where']?$data['where']:[];
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
    
    
    return $where;
    
}


//发送短信
function send_sms($phone,$code){
    
    Vendor('Message.CCP.SDK.CCPRestSDK');
    // 说明：需要包含接口声明文件，可将该文件拷贝到自己的程序组织目录下。
    $accountSid= '8a216da8635e621f01636be55bdd06fe';
    // 说明：主账号，登陆云通讯网站后，可在控制台首页看到开发者主账号ACCOUNT SID。
    
    $accountToken= '288e0cb787524257b00b13209ae1f805';
    // 说明：主账号Token，登陆云通讯网站后，可在控制台首页看到开发者主账号AUTH TOKEN。
    
    $appId='8a216da8635e621f01636be55c350705';
    // 说明：请使用管理控制台中已创建应用的APPID。
    
    $serverIP='app.cloopen.com';
    // 说明：生产环境请求地址：app.cloopen.com。
    
    $serverPort='8883';
    // 说明：请求端口 ，无论生产环境还是沙盒环境都为8883.
    
    $softVersion='2013-12-26';
    // 说明：REST API版本号保持不变。
    
    // ====
    // 初始化REST SDK
    
    $rest = new REST($serverIP,$serverPort,$softVersion);
    $rest->setAccount($accountSid,$accountToken);
    $rest->setAppId($appId);
    
    
    // 发送模板短信
    $result = $rest->sendTemplateSMS($phone,[$code,'10'],'249986');
    if($result == NULL ) {
        return -2;
    }
    if($result->statusCode!=0) {
        echo "模板短信发送失败!";
        echo "error code :" . $result->statusCode . "";
        echo "error msg :" . $result->statusMsg . "";
        return -2;
    }else{
        // 获取返回信息
        return 1;
        //下面可以自己添加成功处理逻辑
    }
    
    
}



// 无论传入的id是数组还是单个，都变成数组返回
function getIds($id){
    $arr=[];
    if(gettype($id)!='array'){
        $arr[]=$id;
    }else{
        $arr=$id;
    }
    return $arr;
}


function alipay($pay_id,$data,$conf,$test=false){
    
    Vendor('Alipay.aop.AopClient');
    Vendor('Alipay.aop.SignData');
    Vendor('Alipay.aop.request.AlipayTradeWapPayRequest');
    
    /*
    http://cosmetics.com/index.php/Home/index/index
    http://cosmetics.com/index.php/Home/index/pay
    http://cosmetics.com/index.php
    */
    
    $aop = new AopClient ();
    
    if($test){
        $aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
        $aop->appId = '2016091500514389';
        $aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEAxVgPE/N1jVkoS8EnEadKlxbzhraZViqtGmi0OssNwGuXrHPeUoY7G8ZplQk9Q3nv9/fkqBSHcNQdqGLVAXVRPL/fKGEksylmfe+51QkiZgt07Pmbp8/cfQRypHQwJAg5HONMDLClF4DXW+fv2avgZfg9aummKY4kq2taAFvIAsBS/CKLvTfPqJOP4JtfOZ9hxR68+qhH2R0tgbhsXM3ySUj39wKV0JMLQlxnn404LGgLcLgzQ4oFX4tzZmCxzQY1U60WU3G+3T7/Vy+UBBIk8JbMgmHnXFQ5hz/AYK4y2OXihpWiEDOYsGj3LnXbgMGhR5keTZGFHas8Vl/DDWrnawIDAQABAoIBAQCPeIvNt2w5DR6spIpg3TzvR3JY+BvWd0RONN0C+WjQAejNKZfya0BB2ygbgBIYImiB4KlOQU6OisfdCa1OWBptjhkRZD6oOmsXF9gEt5VYlu+08Wtmv0nPYhJu2UG+kHPlOqKpfysnQTZQzPilSb3kpGsSdTemcn7aWTufkxqAVEeo/FNIDSrIjFTybEUDxwoPCtsh1rQ+Ok+xhZMH4pNeQyVs6l2/tCTzcRXpZQ0Phn7zmHd6ZiB7xOivgSxu4spg1ovVOl/748cgiMi1t7gNrefNI+dVbGbf2wp2W+kk1WA7QrOsBddWqJuhJTw/+LV0HTmJ28Zq3q240r14sCPRAoGBAONr4dji8zILaJk3p1O2Y/cyRQnVOJQIyKHIOV1leztQ4tBVV2fGIxoRiN3kwye2jqBh6it/ZnmfC9wE3jjZLVFk8edqecQtDEz16xaDaKtmHkYE1hskZ0oGGqKq4O1nmsjUhs3o3oZUAyecwgYuuTw6VAnU6YMXp5FoEFuYEaL1AoGBAN4klualWkvkjAX6xuTF+YXxa5ifH73YcMEW998xUijyoC83wg3o/F3FC2jVdJw9eoS39ZzOsYbn9e8FusKKThQbxeow7BZ3DXIr4d5HEoRwxEwwLVPr6Qs/wavvhCMgeHQC9XwegzUWy/L/8LDPYPc2oJKUX6o3EwU7RRujZaTfAoGACh9SJRos4uxZEoDEpNg4aNdG9WIVK5mcfH7x9rM+oew+vDEgO9E43L8tDVtSSGE6xe61wovgHseyem+JCJS6DKZmkftOQioTIxXLCex9ayuXa1xpvzmGk2fkJZtfeZGj9Q5olZ+oz+fLYCb9B9NqkUCzXuCoKBqUbcdo+vqwxkUCgYEAs3DlAFzzarisLyxau3P8UqkoW/m1vzn2ItN12LHTh0YuBNZKh8f7C5fe1okOkCLNNCYRXeBM4QfAzppXOUxVM8MXhUyNeLwkfWRbKJ1KpPceadjE1LSM5ExVGpj4qRNSmYvGVsOjAyBuENWTzI9H6YoT/TOjbzDbyW8OLPw5YAsCgYEAlHjutxv+tT4RJF+BWSsOKdbX+rdZhINleQ9Lr5NzmPLcRMncfvpS7kMNN/ZJrR1vjPUuYcMtwU6Qk743sB8SpPjKYVa2Sp2/dlirrnhk3fHytkDR3FE/+2oNTI9NekRHst4QaAIF8cJas2yNTlm5iaYTOtsSJhnWcX6OTqtVA0A=';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAucAKl7vxrLXzZ3Fm+hVbOR0IwefcB9q2uhifg69/GqNAuONl8jSuNKP1ujKC8NS8sU27uZ6w88Vi/hz5Z4s9ntoo+qyO3rLk5vW0HYNNRwrR9U+Vcof0Lh19KeyJochYFM33QBzLZRfFUavl6t4FF9khh3zNaNuRXJX8FBLNCiKmpG1L4Yz36AZriHRTC3VctsmhrgDLfAXamB3SAMXzKzkLJh2jofzupW1L9YvYnHYu3gLAwU7qvrrA5S0znYxGQYAczpcgkdvhpwxi37O/IZjdh68BvtZ4MdXT8DFNOO86cHdTNREaD6O0PgtXK8bYqq5NdJIg6tKcnKb45jYgPwIDAQAB';
    }else{
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2018050760118052';
        $aop->rsaPrivateKey = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDhCPJk3Xa+YGj58ASlbMX0PqqN6zSiidpvw2uKrSLBerfScalHlZlUQPjP8mhdZwvPtfLQ+jdp4OWjXQHyZoihMFk0v7r/a/qqnXpbm8BqV+rZ6gQZ5u4Jl34P7rQfJVhlEMj46/tu3jBia+LunK03As9iecjPL0W/fr1XLP7sybN3xcBqKVEiV+ey3RxBUamcfTB0Tsbb4zYMDhu+Un+i4loLiVSsDXC65wEMTgmaZXIzNSE602pzq7mmS0+eQ8Yz3WJVI9YEP8aLpzqQfp1R4Rj0i1+hZAGol3hTGZ+9pPiX7dNfvJE/xuOaBHJKAOYmfFyJhJH9kmT9H9ZyNFTNAgMBAAECggEACzDBtQhaTk9HCR7JbFyDX4j6PEJ1BJwsU6B5KZVAhDSRDZL/YwLqdAkjFlwsmnB0YujeYUwAE3DOmpgWb7JvmuxnkzrxlqANAA0Ct6mAIQmsMRf1CkC0l5+D8lDhficCRUxXYiGCCFpeN5a72zfJWVH3dCMwPDVSl5o62invp2CseZ74SrH1fCubV2QVyTCY2Taq72/WXrxpFpMbfiSLZTcgfuLqFc1jtI3FDpfOHpoInRj/lweDdw8TBO2bd3tY9oRVbJ6Ebm3i9dXLEvRub+N0GWFMS+w45PAnCcH6AgJXN2vm1Z8TVKUtuceS1VzXt1IwP8YDKDGVVkHk3oHj1QKBgQD7LoS+SQbAAQ9if09yj+eHGyeQP5kRgbh7agNlZiNZr19cKZ8KZKyksFOOTpIH5ybBBvSJKGg6077e/flHkuFsUeDwbeVreAyJR3DVUkvnxLuh74DRLGRIS41UKy82HQhswdz2y+7J91ydsB5uU0sNdVgDd2EqdkxF+lGRFOX9SwKBgQDlWgdv0F07GxILNfR5IGDIvElIEAtEMVZWy4B0u/Eg8VmKBIY3ry+pj8Mo38IT5Gf+TOG6ZZjJJfh5QNGRgMFJATn0nfWsYNCMO5dV3dqfN7DRDZJ58Ax2vS7nwbgANk29vDaL3RmBjQeBJRpcfxn+YkiYEoKxbewPJ/FLid0fRwKBgGXXeqLcYQxAYciB7eh5SFqTO/tUje93NSF03mOigfq/DF8F0SIZp5Yul/I/ER57Lk9dTpC9/WS8rcskFopal2Of3yAcrsRgLFUMzkbv0y7pqVoDDavB3/cgCvFxgPbj1qKiB8FY9jyVOswEuJHq26ddSI4/PPpGdK56y8+TNZz5AoGBAJnwd5VhMXJ+lPnZWSIDzXJujnFD84vt4ZmYNbwkeZA27nZo2v19JGyXBdLlfQqFABf72naVwpsgVUptazxSQ2mQ3SmG7GKrCM9XIBiONDMx2gg3J92YdYUtLdJ4f9wEluGG9sWhvYDq++J+2NbEqNnJdhg8NGSK9yqlEJw3CKf5AoGBALteW/+EfQxWqEn5yBjje+viEnmMOL8UahopDf0+Aphm35IOFTUJMoROylki9NM7I4wyu0IrlHCXNxt9xt3xfJrEEfKnfVOh5lTD7FDEQlHpL9d4funU45WiluBhBXI1ALbEHcurLKz8rBShgmw9fAyHFcIbR/DiyWivAtJi4rCY';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApHXKTQ8bgJTImUwZ/2aDcOG0AaNZjPFUVsZq/mqsDqrCpEOuepEsWE0eTd/TXP8vXpnnQzl2LPW6XHme7+e9LrkSUJH+8SHRBteaWUwCj8WOkfBiTvnSKTFw4RNhujZWH44dxZ5MNiXydOT7inHkZkKz0+Jhzkgz0yXx7wE4PYV0er6bJBOvnaOyQ3o5nECyBWdOQFIO9RFA1LQZ+nJrIZCfSTpHj7PQcA3zvLcaUZVjgcErJpqEKOteSC77ksWaSH3RDVsC2mEhDODZinch654ltwS8PNBBi1uXFL9+3Rx33gmq7igigvRRheZqZKn0OjbmZc2kqeAmg9Zx/gBDWQIDAQAB';
    }
    
    
    
    $aop->apiVersion = '1.0';
    $aop->postCharset='UTF-8';
    $aop->format='json';
    $aop->signType='RSA2';
    $request = new AlipayTradeWapPayRequest ();
    
    $request->setNotifyUrl($conf['notify_url']);
    $request->setBizContent(json_encode($data));//构建数据
    $result = $aop->pageExecute ( $request);
    echo $result;
}

function get_float_length($a){
    if(($a-(int)$a)<1E-9){
        $count=0;
    }
    $a=fmod(1E9*$a,1E9);
    while($a%10==0){
        if($a==0) break ;
        $a=$a/10;
        $count--;
    }
    return count;
}

function weixin(){
    // unifiedOrder
    Vendor('Weixin.WxPayApi');
    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetBody("test");
    $input->SetAttach("test");
    $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
    $input->SetTotal_fee("1");
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test");
    $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
    $input->SetTrade_type("JSAPI");
    // $input->SetOpenid($openId);
    $order = WxPayApi::unifiedOrder($input);
    echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
    printf_info($order);
    $jsApiParameters = $tools->GetJsApiParameters($order);
    
    //获取共享收货地址js函数参数
    $editAddress = $tools->GetEditAddressParameters();
    
    //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
    /**
    * 注意：
    * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
    * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
    * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
    */
}