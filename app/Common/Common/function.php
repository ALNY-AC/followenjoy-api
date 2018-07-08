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
    $login_id=I($table."_id");
    $token=I('token');
    
    if(!$login_id || !$token){
        //没有参数
        return -992;
        
    }
    
    $where['login_id']=$login_id;
    $where['token']=$token;
    $Token=D('Token');
    $result=$Token->where($where)->find();
    
    
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
        // echo "模板短信发送失败!";
        // echo "error code :" . $result->statusCode . "";
        // echo "error msg :" . $result->statusMsg . "";
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


//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

function weixin($data){
    // unifiedOrder
    //①、获取用户openid
    ini_set('date.timezone','Asia/Shanghai');
    
    Vendor('Weixin.pay1.WxPayJsApiPay');
    Vendor('Weixin.pay1.WxPayApi');
    
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid();
    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetBody($data['body']);
    // $input->SetAttach("test");
    $input->SetOut_trade_no($data['out_trade_no']);
    $input->SetTotal_fee($data['total_fee']*100);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test");
    $input->SetNotify_url( U('Home/WeiXinPay/notify',null,null,true));
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    
    // $input->SetAppid('wx56a5a0b6368f00a7');//公众账号ID
    // $input->SetMch_id('1501688321');//商户号
    
    $order = WxPayApi::unifiedOrder($input);
    // echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
    // printf_info($order);
    // sandboxnew
    $jsApiParameters = $tools->GetJsApiParameters($order);
    
    // ===================================================================================
    
    return $jsApiParameters;
    // https://api.mch.weixin.qq.com/pay/unifiedorder
    
    
    //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
    /**
    * 注意：
    * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
    * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
    * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
    */
}

/**
* 以post方式提交xml到对应的接口url
*
* @param string $xml  需要post的xml数据
* @param string $url  url
* @param bool $useCert 是否需要证书，默认不需要
* @param int $second   url执行超时时间，默认30s
* @throws WxPayException
*/
function postXmlCurl($xml, $url, $useCert = false, $second = 30)
{
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    
    //如果有配置代理这里就设置代理
    if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
    && WxPayConfig::CURL_PROXY_PORT != 0){
        curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
        curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
    }
    curl_setopt($ch,CURLOPT_URL, $url);
    
    
    if(stripos($url,"https://")!==FALSE){
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
}    else    {
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
}

// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
// curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验

//设置header
curl_setopt($ch, CURLOPT_HEADER, FALSE);
//要求结果为字符串且输出到屏幕上
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

if($useCert == true){
    //设置证书
    //使用证书：cert 与 key 分别属于两个.pem文件
    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
    curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
}
//post提交方式
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
//运行curl
$data = curl_exec($ch);
//返回结果
if($data){
    curl_close($ch);
    return $data;
} else {
    $error = curl_errno($ch);
    curl_close($ch);
    throw new WxPayException("curl出错，错误码:$error");
}
}

function weixinApp($data){
    
    // Vendor('Weixin.pay2.WxPayApi');
    // $input = new WxPayUnifiedOrder();
    
    
    
    // $mch_id='1501688321';
    // $nonce_str=getNonceStr();
    // $key='8312162ee470f489870f1fd35288a946';
    
    // $data=[];
    // $data['mch_id']=$mch_id;
    // $data['nonce_str']=$nonce_str;
    // $sign=makeSign($data,$key);
    // $data['sign']=$sign;
    
    // $input->values=$data;
    
    // $xml=$input->ToXml();
    // dump($xml);
    
    // $url="https://api.mch.weixin.qq.com/sandboxnew/pay/getsignkey";
    
    // $response = postXmlCurl($xml, $url, false, 6);
    // dump($response);
    // 813ef3e0dfce44063f8572cfecdb669f
    // die;
    
    // unifiedOrder
    ini_set('date.timezone','Asia/Shanghai');
    
    Vendor('Weixin.pay2.WxPayApi');
    
    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetBody($data['body']);
    $input->SetOut_trade_no($data['out_trade_no']);
    $input->SetTotal_fee($data['total_fee']*100);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test");
    $input->SetNotify_url(U('Home/WeiXinPay/notifyApp',null,null,true));
    $input->SetTrade_type("APP");
    
    
    $order = WxPayApi::unifiedOrder($input);
    $values=$input->values;
    // ===================================================================================
    // 处理预订单
    // getNonceStr
    $prepay_id=$order['prepay_id'];
    $prepayData=[];
    $noncestr=$values['nonce_str'];
    
    $timestamp=''.time().'';
    
    $prepayData['appid']='wx8c3b0269e9e2c724';//appid
    $prepayData['partnerid']='1504196381';//商户号
    $prepayData['prepayid']=$prepay_id;//预订单号
    $prepayData['package']='Sign=WXPay';//扩展字段
    $prepayData['noncestr']=$noncestr;//随机字符串
    $prepayData['timestamp']=$timestamp;
    $key='8312162ee470f489870f1fd35288a946';
    $sign=makeSign($prepayData,$key);
    $prepayData['sign']=$sign;
    // $prepayData['retcode']=0;
    // $prepayData['retmsg']="ok";
    
    
    return $prepayData;
}

// 生成签名
function makeSign($values,$key){
    //签名步骤一：按字典序排序参数
    ksort($values);
    $string = toUrlParams($values);
    //签名步骤二：在string后加入KEY
    $string = $string . "&key=".$key;
    //签名步骤三：MD5加密
    $string = md5($string);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($string);
    return $result;
}

// arr转url参数
function toUrlParams($values)
{
    $buff = "";
    foreach ($values as $k => $v)
    {
        if($k != "sign" && $v != "" && !is_array($v)){
            $buff .= $k . "=" . $v . "&";
        }
    }
    
    $buff = trim($buff, "&");
    return $buff;
}

function getNonceStr($length = 32)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}


function ToXml($data)
{
    
    $xml = "<xml>";
    foreach ($data as $key=>$val)
    {
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
}

function getsignkey()
{
    
    
}

//设置网络请求配置
function _request($curl,$https=true,$method='GET',$data=null){
    // 创建一个新cURL资源
    $ch = curl_init();
    
    // 设置URL和相应的选项
    curl_setopt($ch, CURLOPT_URL, $curl);    //要访问的网站
    curl_setopt($ch, CURLOPT_HEADER, false);    //启用时会将头文件的信息作为数据流输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //将curl_exec()获取的信息以字符串返回，而不是直接输出。
    
    if($https){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //FALSE 禁止 cURL 验证对等证书（peer's certificate）。
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  //验证主机
    }
    if($method == 'POST'){
        curl_setopt($ch, CURLOPT_POST, true);  //发送 POST 请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //全部数据使用HTTP协议中的 "POST" 操作来发送。
    }
    
    
    // 抓取URL并把它传递给浏览器
    $content = curl_exec($ch);
    if ($content  === false) {
        return "网络请求出错: " . curl_error($ch);
        exit();
    }
    //关闭cURL资源，并且释放系统资源
    curl_close($ch);
    // http://127.0.0.1:12138/wShop/index.php/home/test/index
    return $content;
}




/**
* 获取用户的openid
* @param  string $openid [description]
* @return [type]         [description]
*/
function baseAuth($redirect_url){
    
    // $appid='wx9b7ab18e61268efb';
    // $appsecret='bcd46807674b9448617438256db6cada';
    //===
    $appid='wxc5919bd34da8b695';
    $appsecret='87e678bca54b92f8c7213e1ba9f12963';
    
    
    //1.准备scope为 snsapi_base 网页授权页面 snsapi_userinfo
    
    $baseurl = urlencode($redirect_url);
    $snsapi_base_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$baseurl.'&response_type=code&scope=snsapi_userinfo&state=YQJ#wechat_redirect';
    
    //2.静默授权,获取code
    //页面跳转至redirect_uri/?code=CODE&state=STATE
    $code = $_GET['code'];
    if( !isset($code) ){
        header('Location:'.$snsapi_base_url);
    }
    
    //3.通过code换取网页授权access_token和openid
    $curl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
    $content =_request($curl);
    $result = json_decode($content,true);
    
    return $result;
}

function randomFloat($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}








/**
* 分享图片生成
* @param $gData  商品数据，array
* @param $codeName 二维码图片
* @param $fileName string 保存文件名,默认空则直接输入图片
*/
function createSharePng($gData,$codeName,$fileName = ''){
    
    
    $width=1080;
    $height=1900;
    $left=50;
    
    //创建画布
    $im = imagecreatetruecolor($width, $height);
    
    //填充画布背景色
    $color = imagecolorallocate($im, 35, 35, 35);
    imagefill($im, 0, 0, $color);
    
    // 字体文件
    $font_file = "./Public/ttf/PingFang Regular.ttf";
    $font_file2 = "./Public/ttf/PingFang Medium.ttf";
    $font_file_bold = "./Public/ttf/PingFang Heavy.ttf";
    
    //设定字体的颜色
    $font_color_1 = ImageColorAllocate ($im, 38, 38, 38);
    $font_color_2 = ImageColorAllocate ($im, 250, 250, 250);
    
    $font_color_red = ImageColorAllocate ($im, 217, 45, 32);
    
    $font_color_3 = ImageColorAllocate ($im, 186, 163, 113);
    
    
    $fang_bg_color = ImageColorAllocate ($im, 250, 250, 250);
    $fang_bg_color2 = ImageColorAllocate ($im, 254, 216, 217);
    
    $fontSize=30;
    
    
    $userImgTop=100;
    // ===================================================================================
    // 分享人的头像
    list($g_w,$g_h) = getimagesize($gData['user_head']);
    $userImg = createImageFromFile($gData['user_head']);
    $userImgSize=80;
    imagecopyresized($im, $userImg, $left, $userImgTop, 0, 0,  $userImgSize,  $userImgSize, $g_w, $g_h);
    
    
    // ===================================================================================
    // 名字的背景
    $strWidth=imagettfbbox($fontSize-8,0,$font_file,$gData['user_name'])[2];
    $labelWidth=$strWidth +40;
    
    imagefilledrectangle ($im, $left + $userImgSize+1 , $userImgTop+ $userImgSize -1 , $left + $userImgSize+1+$labelWidth, $userImgTop+30 , $fang_bg_color);
    
    // ===================================================================================
    // 分享人名称
    imagettftext($im,  $fontSize-8,0,($left+$userImgSize) + $labelWidth/2 - $strWidth/2, $userImgTop+$userImgSize-14 , $font_color_1,$font_file, $gData['user_name']);
    
    
    
    // imagecopyresized($im, $codeImg, $codeX, $codeY, 0, 0, $codeWidth, $codeWidth, $code_w, $code_h);
    
    // 一级标题
    $titleYTop=250;
    $titleDev=$fontSize+10;
    $theTitle = cn_row_substr($gData['title'],2,19);
    $count=0;
    foreach ($theTitle as $k => $v) {
        if($v){
            imagettftext($im,  $fontSize-5,0, $left, $titleYTop+($count)*$titleDev, $font_color_2 ,$font_file2, $theTitle[$count+1]);
            $count++;
        }
    }
    
    // 二级标题
    $subTop=$titleYTop+$titleDev*$count+10;
    $sub_title = cn_row_substr($gData['sub_title'],2,19);
    $font_color_2 = ImageColorAllocate($im, 190, 190, 190);
    $count=0;
    $imgTop=0;
    foreach ($sub_title as $k => $v) {
        if($v){
            imagettftext($im,  $fontSize-7,0, $left, $subTop+($count)*$titleDev, $font_color_2 ,$font_file, $sub_title[$count+1]);
            $count++;
        }
    }
    
    $imgTop=$subTop+($count)*$titleDev+30;
    
    
    // ===================================================================================
    // 商品图片上的装饰
    // imagefilledrectangle ($im, $width/1.7 , $imgTop-3, $width, $imgTop-10 , $fang_bg_color);
    
    // ===================================================================================
    // 商品图片下的装饰
    // imagefilledrectangle ($im, 0, $imgTop+$width+13, $width/2.3, $imgTop+$width+3 , $fang_bg_color);
    
    // ===================================================================================
    // 商品图片
    list($g_w,$g_h) = getimagesize($gData['pic']);
    $goodImg = createImageFromFile($gData['pic']);
    imagecopyresized($im, $goodImg, 0, $imgTop, 0, 0, $width, $width, $g_w, $g_h);
    
    
    
    // ===================================================================================
    // 如果有活动
    if($gData['is_time']){
        
        // $top=$priceTop + $fontSize+10;
        
        // 238, 37, 50
        // $fang_bg_color = ImageColorAllocate ($im, 238, 37, 50);
        
        // imagerectangle ($im, 125 , 950 , 220 , 975 , $fang_bg_color);
        // imagefilledrectangle ($im, $left , $top , $left+170 , $top+50 , $fang_bg_color);
        
        // imagettftext($im, $fontSize,0, $left+5,$top+$fontSize+10, $font_color_3 ,$font_file, "限时特卖");
        
        // imagerectangle ($im,  $left+170 , $top , $left+170+400 , $top+50 , $fang_bg_color);//开抢的框框
        
        // imagettftext($im, $fontSize,0, $left+170+10,$top+40, $fang_bg_color ,$font_file, '06月30日 11:00开抢');
        
    }
    
    
    
    // ===================================================================================
    // 二维码
    list($code_w,$code_h) = getimagesize($codeName);
    $codeImg = createImageFromFile($codeName);
    $codeWidth=$width/5;
    $codeHeight=$codeWidth;
    $codeY=$imgTop+$width+100;
    $codeX=$width-$codeWidth-$left;
    imagecopyresized($im, $codeImg, $codeX, $codeY, 0, 0, $codeWidth, $codeHeight, $code_w, $code_h);
    
    
    
    // ===================================================================================
    // 二维码提示符
    
    imagettftext($im, $fontSize-5,0, $codeX-1, $codeY-17, $font_color_2 ,$font_file, '[长按立即购买]');
    
    
    
    // ===================================================================================
    // 价格的left
    $priceTop=$imgTop+$width+180;
    
    if($gData['origin']){
        
        // 方块
        
        // ===================================================================================
        // 限时特卖
        $fang_bg_color = ImageColorAllocate ($im, 186, 163, 113);
        $font_color_8 = ImageColorAllocate($im, 250, 250, 250);
        
        imagefilledrectangle ($im, 80 , $priceTop+5 , 80+150, $priceTop-50 , $fang_bg_color);
        
        imagettftext($im, $fontSize-9 ,0 , 100  , $priceTop -12, $font_color_8 ,$font_file, '限时特卖');
        
        $priceLeft=240;
        imagettftext($im, $fontSize-3 ,0 , $priceLeft  , $priceTop , $font_color_3 ,$font_file_bold, '￥：');
        imagettftext($im, $fontSize+15 , 0 , $priceLeft+50 , $priceTop , $font_color_3 ,$font_file_bold, $gData["price"]);
        
        
    }else{
        
        $priceLeft=130;
        $priceTop+=70;
        imagettftext($im, $fontSize+10 ,0 , $priceLeft  , $priceTop , $font_color_3 ,$font_file_bold, 'RMB：');
        imagettftext($im, $fontSize+30 , 0 , $priceLeft+150 , $priceTop , $font_color_3 ,$font_file_bold, $gData["price"]);
        
    }
    
    // ===================================================================================
    // 价格的icon
    
    
    // ===================================================================================
    // 价格
    
    
    
    if($gData['origin']){
        
        
        // ===================================================================================
        // 原价
        $str=$gData["origin"]['time'];
        $font_color_2 = ImageColorAllocate($im, 250, 250, 250);
        imagettftext($im, $fontSize-10 , 0 , 80, $priceTop+$fontSize+15 , $font_color_2 ,$font_file_bold, $str);
        
        
    }
    
    /***
    
    http://cosmetics.cn/index.php/Home/Share/getGoodsImage?down=true&user_id=13914896237&goods_id=33&href=http%3A%2F%2Fq.followenjoy.cn%2F%23%2FgoodsInfo%3F%26goods_id%3D33%26share_id%3D13914896237%26shop_id%3D92868559
    http://cosmetics.cn/index.php/Home/Share/getGoodsImage?down=true&user_id=13914896237&goods_id=33&href=http%3A%2F%2Fq.followenjoy.cn%2F%23%2FgoodsInfo%3F%26goods_id%3D33%26share_id%3D13914896237%26shop_id%3D92868559
    
    
    http://cosmetics.cn/index.php/Home/Share/getGoodsImage?down=true&user_id=13914896237&goods_id=32&href=http%3A%2F%2Fq.followenjoy.cn%2F%23%2FgoodsInfo%3F%26goods_id%3D32%26share_id%3D13914896237%26shop_id%3D92868559
    http://cosmetics.cn/index.php/Home/Share/getGoodsImage?down=true&user_id=13914896237&goods_id=32&href=http%3A%2F%2Fq.followenjoy.cn%2F%23%2FgoodsInfo%3F%26goods_id%3D32%26share_id%3D13914896237%26shop_id%3D92868559
    
    
    
    http://cosmetics.cn/index.php/Home/Share/getGoodsImage?down=true&user_id=13914896237&goods_id=33&href=http%3A%2F%2Fq.followenjoy.cn%2F%23%2FgoodsInfo%3F%26goods_id%3D33%26share_id%3D13914896237%26shop_id%3D92868559&rand=0.9334339965619542 at static/js/app.b6a045c40b8cdea23ce7.js:1
    http://cosmetics.cn/index.php/Home/Share/getGoodsImage?down=true&user_id=13914896237&goods_id=33&href=http%3A%2F%2Fq.followenjoy.cn%2F%23%2FgoodsInfo%3F%26goods_id%3D33%26share_id%3D13914896237%26shop_id%3D92868559&rand=0.9334339965619542 at static/js/app.b6a045c40b8cdea23ce7.js:1
    S
    
    *
    *
    *
    */
    // dump($fileName);
    // die;
    
    //输出图片
    if($fileName){
        // ob_clean();
        $fileName='Public/Assets/Goods/'.$fileName;
        Header("Content-Type: image/png");
        imagepng ($im,$fileName);
        
        // //告诉浏览器这是一个文件流格式的文件
        // Header ( "Content-type: application/octet-stream" );
        // //请求范围的度量单位
        // Header ( "Accept-Ranges: bytes" );
        // //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        // Header ( "Content-Disposition: attachment; filename=" . $fileName );
        // readfile($fileName);
        
        // //释放空间
        // imagedestroy($im);
        // imagedestroy($goodImg);
        // imagedestroy($codeImg);
        // exit;
        download($fileName);
    }else{
        
        Header("Content-Type: image/png");
        imagepng ($im);
        
    }
    
    //释放空间
    imagedestroy($im);
    imagedestroy($goodImg);
    imagedestroy($codeImg);
}

function download($file_url,$new_name=''){
    if(!isset($file_url)||trim($file_url)==''){
        echo '500';
    }
    if(!file_exists($file_url)){ //检查文件是否存在
        echo '404';
    }
    $file_name=basename($file_url);
    $file_type=explode('.',$file_url);
    $file_type=$file_type[count($file_type)-1];
    $file_name=trim($new_name=='')?$file_name:urlencode($new_name);
    $file_type=fopen($file_url,'r'); //打开文件
    //输入文件标签
    header("Content-type: application/octet-stream");
    header("Accept-Ranges: bytes");
    header("Accept-Length: ".filesize($file_url));
    header("Content-Disposition: attachment; filename=".$file_name);
    //输出文件内容
    echo fread($file_type,filesize($file_url));
    fclose($file_type);
}



/**
* 从图片文件创建Image资源
* @param $file 图片文件，支持url
* @return bool|resource    成功返回图片image资源，失败返回false
*/
function createImageFromFile($file){
    
    if(preg_match('/http(s)?:\/\//',$file)){
    
    // $fileSuffix = getNetworkImgType($file);
    $fileSuffix= getimagesize($file)['mime'];
    
}else{
    // $fileSuffix = pathinfo($file, PATHINFO_EXTENSION);
    $fileSuffix= getimagesize($file)['mime'];
}


if(!$fileSuffix) return false;


switch ($fileSuffix){
    case 'jpeg' :
        case 'image/jpeg' :
            $theImage = @imagecreatefromjpeg($file);
            break ;
            case 'jpg' :
                $theImage = @imagecreatefromjpeg($file);
                break ;
                case 'png' :
                    
                    $theImage = @imagecreatefrompng($file);
                    
                    break ;
                    case 'gif' :
                        $theImage = @imagecreatefromgif($file);
                        break ;
                        default :
                            $theImage = @imagecreatefromstring(file_get_contents($file));
                            break ;
                    }
                    
                    
                    return $theImage;
                    
                }
                
                /**
                * 获取网络图片类型
                * @param $url  网络图片url,支持不带后缀名url
                * @return bool
                */
                function getNetworkImgType($url){
                    $ch = curl_init(); //初始化curl
                    curl_setopt($ch, CURLOPT_URL, $url); //设置需要获取的URL
                    curl_setopt($ch, CURLOPT_NOBODY, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//设置超时
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //支持https
                    curl_exec($ch);//执行curl会话
                    $http_code = curl_getinfo($ch);//获取curl连接资源句柄信息
                    curl_close($ch);//关闭资源连接
                    
                    if ($http_code['http_code'] == 200) {
                        $theImgType = explode('/',$http_code['content_type']);
                        
                        if($theImgType[0] == 'image'){
                            return $theImgType[1];
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
                
                /**
                * 分行连续截取字符串
                * @param $str  需要截取的字符串,UTF-8
                * @param int $row  截取的行数
                * @param int $number   每行截取的字数，中文长度
                * @param bool $suffix  最后行是否添加‘...’后缀
                * @return array    返回数组共$row个元素，下标1到$row
                */
                function cn_row_substr($str,$row = 1,$number = 10,$suffix = true){
                    $result = array();
                    for ($r=1;$r<=$row;$r++){
                        $result[$r] = '';
                    }
                    
                    $str = trim($str);
                    if(!$str) return $result;
                    
                    $theStrlen = strlen($str);
                    
                    //每行实际字节长度
                    $oneRowNum = $number * 3;
                    for($r=1;$r<=$row;$r++){
                        if($r == $row and $theStrlen > $r * $oneRowNum and $suffix){
                            $result[$r] = mg_cn_substr($str,$oneRowNum-6,($r-1)* $oneRowNum).'...';
                        }else{
                            $result[$r] = mg_cn_substr($str,$oneRowNum,($r-1)* $oneRowNum);
                        }
                        if($theStrlen < $r * $oneRowNum) break ;
                    }
                    
                    return $result;
                }
                
                /**
                * 按字节截取utf-8字符串
                * 识别汉字全角符号，全角中文3个字节，半角英文1个字节
                * @param $str  需要切取的字符串
                * @param $len  截取长度[字节]
                * @param int $start    截取开始位置，默认0
                * @return string
                */
                function mg_cn_substr($str,$len,$start = 0){
                    $q_str = '';
                    $q_strlen = ($start + $len)>strlen($str) ? strlen($str) : ($start + $len);
                    
                    //如果start不为起始位置，若起始位置为乱码就按照UTF-8编码获取新start
                    if($start and json_encode(substr($str,$start,1)) === false){
                        for($a=0;$a<3;$a++){
                            $new_start = $start + $a;
                            $m_str = substr($str,$new_start,3);
                            if(json_encode($m_str) !== false) {
                                $start = $new_start;
                                break ;
                            }
                        }
                    }
                    
                    //切取内容
                    for($i=$start;$i<$q_strlen;$i++){
                        //ord()函数取得substr()的第一个字符的ASCII码，如果大于0xa0的话则是中文字符
                        if(ord(substr($str,$i,1))>0xa0){
                            $q_str .= substr($str,$i,3);
                            $i+=2;
                        }else{
                            $q_str .= substr($str,$i,1);
                        }
                    }
                    return $q_str;
                }
                
                
                function rsa($data){
                    return base64_encode($data);
                }
                function rsa2($data){
                    return base64_decode($data);
                }