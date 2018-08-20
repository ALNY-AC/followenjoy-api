<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月4日18:07:22
* 最新修改时间：2018年4月4日18:07:22
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####专题控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class SpecialController extends Controller{
    
    //获得专题页数据包
    public function getPacket(){
        $Special=D('Special');
        $special=$Special->get(I('special_id'));
        
        if($special){
            $res['res']=1;
            $res['msg']=$special;
        }else{
            $res['res']=-1;
            $res['msg']=$special;
        }
        echo json_encode($res);
        
    }
    
    public function getList(){
        $Special=D('Special');
        $specials=$Special->getList(I());
        if($specials){
            $res['res']=count($specials);
            $res['msg']=$specials;
        }else{
            $res['res']=-1;
            $res['msg']=$specials;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        $Special=D('Special');
        $specials=$Special->get(I('special_id'));
        if($specials){
            $res['res']=1;
            $res['msg']=$specials;
        }else{
            $res['res']=-1;
            $res['msg']=$specials;
        }
        echo json_encode($res);
    }
    
    
    public function getInfo(){
        
        $Special=D('Special');
        $special_id=I('special_id');
        $where=[];
        $where['special_id']=$special_id;
        $result=$Special
        ->cache(true,60)
        ->where($where)
        ->find();
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function getGoodsList(){
        
        $res=[];
        
        // ===================================================================================
        // 创建模型
        $SpecialGoods=D('SpecialGoods');
        $Goods=D('Goods');
        
        // ===================================================================================
        // 组装数据
        $special_id=I('special_id');
        
        // ===================================================================================
        // 先取分页专题关联的商品id
        $where=[];
        // $where['special_id']=$special_id;
        
        $page=I('page',1,false);
        $page_size=I('page_size',5,false);
        
        $list=$SpecialGoods
        ->distinct(true)
        ->cache(true,60)
        ->table('c_special_goods as t1,c_goods as t2')
        ->field('t1.*,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title,t2.sort,t2.add_time')
        ->order('t2.sort desc,t2.add_time desc')
        ->where("t1.special_id='$special_id' AND t1.goods_id = t2.goods_id AND t2.is_up = '1'")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        // $res['total']=$SpecialGoods
        // ->where($where)
        // ->count();
        
        // $where=[];
        // $where['goods_id']=['in',getIds($ids)];
        
        // $field=[
        // 'goods_id',
        // 'goods_title',
        // 'goods_banner',
        // 'sub_title',
        // // 'freight_id',
        // // 'is_up',
        // // 'goods_class',
        // 'sort',
        // // 'is_cross_border',
        // // 'goods_content',
        // // 'is_unique',
        // // 'add_time',
        // // 'edit_time'
        // ];
        
        // $list=$Goods
        // ->order('sort desc,add_time desc')
        // ->where($where)
        // ->field($field)
        // ->select();
        foreach ($list as $k => $v) {
            $v=$Goods->getGoodsSku($v,['img_list','sku'],['img_list'=>1,'sku'=>1]);
            $v=$Goods->getTime($v);
            $list[$k]=$v;
        }
        if($list!==false){
            $res['res']=count($list);
            $res['msg']=$list;
        }else{
            $res['res']=0;
        }
        echo json_encode($res);
        
    }
}