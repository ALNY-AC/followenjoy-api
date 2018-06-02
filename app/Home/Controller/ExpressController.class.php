<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月19日13:40:12
* 最新修改时间：2018年5月19日13:40:12
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####物流信息控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class ExpressController extends CommonController{
    
    public function get(){
        $post_data = array();
        $post_data["customer"] = '86545A2CEDCDC0A43AF74F4870B13815';
        $key= 'YmFAdKBv452' ;
        
        $param=[];
        $param['com']=I('com');
        $param['num']=I('num');
        
        $post_data["param"] =json_encode($param);
        $url='http://poll.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        $data = str_replace("\"",'"',$result );
        $data = json_decode($data,true);
    }
    
}