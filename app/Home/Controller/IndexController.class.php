<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日15:23:103
* 最新修改时间：2018年4月8日15:23:103
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####主控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{
    
    public function index(){
        echo "随享季1";
    }
    
    public function test(){
        
        
        $Goods=D('Goods');
        
        
        $field=[
        'goods_id',
        'goods_title',
        // 'goods_banner',
        // 'sub_title',
        // 'freight_id',
        // 'is_up',
        // 'goods_class',
        // 'sort',
        // 'is_cross_border',
        'goods_content',
        // 'is_unique',
        // 'add_time',
        // 'edit_time'
        ];
        $where['goods_id']=171;
        $list=$Goods
        ->field($field)
        ->where($where)
        ->order('goods_id asc')
        // ->limit(2)
        ->select();
        
        $saveArr=[];
        foreach ($list as $k => $v) {
            
            $goods_content=$v['goods_content'];
            
            // $v['goods_content']=str_replace('hyu2595240001.my3w.com','server.followenjoy.cn',$goods_content);
            $goods_content=str_replace('<p>','',$goods_content);
            $goods_content=str_replace('</p>','',$goods_content);
            $v['goods_content']=$goods_content;
            
            $item=[];
            $item['goods_id']=$v['goods_id'];
            $item['save']=['goods_content'=>$v['goods_content']];
            $saveArr[]=$item;
            
        }
        
        foreach ($saveArr as $k => $v) {
            $where=[];
            $goods_id=$v['goods_id'];
            $where['goods_id']=$v['goods_id'];
            $save=$v['save'];
            // $is=$Goods->where($where)->save($save);
            if($is){
                ec("商品【 $goods_id 】保存成功",'p','color:#0f0');
            }else{
                ec("商品【 $goods_id 】保存失败！",'p','color:#f00');
            }
            
        }
        
        
        dump($list);
        dump($saveArr);
        
    }
    
    public function pay(){
        
        ec('支付测试','h2');
        
        $PayLog=D('PayLog');
        // alipay();
        
    }
}