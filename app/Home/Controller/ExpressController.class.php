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
class ExpressController extends Controller{
    
    public function test(){
        $com="申通快递";
        $nu="3361438324750";
        $context="查询";
        $link="<a href='https://m.kuaidi100.com/index_all.html?type=".$com."&postid=".$nu."'>".$context."</a>";
        
        
        
        //   // http://api.kuaidi100.com/api?id=[]&com=[]&nu=[]&valicode=[]&show=[0|1|2|3]&muti=[0|1]&order=[desc|asc]
        
        //   this.$get('http://api.kuaidi100.com/api', {
        //     logistics_name: this.logistics_name,
        //     id: "",
        //     com: '',
        //     nu: this.logistics_number,
        // }, res => {
        //     console.warn(res);
        // });
        
        
        
        echo $link;
    }
    
    public function get(){
        $id='';
        $com=I('com');
        $nu=I('nu');
        $api="http://api.kuaidi100.com/api?id=$id&com=$com&nu=$$nu&show=0&order=desc";
        
        include("snoopy.php");
        $snoopy = new snoopy();
        $snoopy->referer = 'http://www.google.com/';//伪装来源
        $snoopy->fetch($url);
        $get_content = $snoopy->results;
    }
    
}