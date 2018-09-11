<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月27日23:32:56
* 最新修改时间：2018年5月27日23:32:56
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####限时购商品控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class TimeGoodsController extends CommonController{
    
    public function create(){
        $TimeGoods=D('TimeGoods');
        $result=$TimeGoods->create(I());
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getList(){
        $TimeGoods=D('TimeGoods');
        
        $data=I();
        
        $result=$TimeGoods->getList($data);
        
        $start_time=$data['start_time'];
        
        $res['count']=$TimeGoods->where(['start_time'=>$start_time])->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getAll(){
        $TimeGoods=D('TimeGoods');
        $data=I();
        $result=$TimeGoods->getAll($data);
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        $TimeGoods=D('TimeGoods');
        $result=$TimeGoods->del(I('goods_id'),I('start_time'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function getData(){
        
        $TimeGoods=D('TimeGoods');
        
        $result=$TimeGoods->getData(I());
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function saveData(){
        
        $TimeGoods=D('TimeGoods');
        
        $result=$TimeGoods->saveData(I('goods_id'),I('start_time'),I('data'));
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function test(){
        
        die;
        $TimeGoods=D('TimeGoods');
        
        $list=$TimeGoods->select();
        
        foreach ($list as $k => $v) {
            
            $where=[];
            $where['time_goods_id']=$v['time_goods_id'];
            
            $data=[];
            $end_time=strtotime("+1 day",$v['start_time']);
            // $data['start_time']=$end_time;
            $data['end_time']=$end_time;
            
            $TimeGoods->where($where)->save($data);
            
        }
        $list=$TimeGoods->select();
        
        dump($list);
        
    }
    
    
    public function getListPlus(){
        
        $start_time=I('start_time');
        $page=I('page');
        $page_size=I('page_size');
        
        $TimeGoods=D('TimeGoods');
        $Goods=D('Goods');
        
        
        $goodsList=$TimeGoods
        ->distinct(true)
        ->table('c_time_goods as t1,c_goods as t2')
        ->field('t1.*,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title,t2.sort,t2.add_time,t2.is_up')
        ->order('t2.sort desc,t2.add_time desc')
        ->where("t1.start_time='$start_time' AND t1.goods_id = t2.goods_id")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        
        $where=[];
        $where['start_time']=$start_time;
        $res['count']=$TimeGoods
        ->where($where)
        ->count()+0;
        
        $GoodsImg=D('GoodsImg');
        $Sku=D('sku');
        
        foreach ($goodsList as $k => $v) {
            $where=[];
            $where['goods_id']=$v['goods_id'];
            $v['goods_head']=$GoodsImg->order('slot asc')->where($where)->getField('src');
            $v['price']=$Sku->where($where)->getField('price');
            $v['stock_num']=$Sku->where($where)->sum('stock_num');
            $v['priceList']=$Sku->where($where)->field('price,activity_price')->select();
            $goodsList[$k]=$v;
        }
        
        if($goodsList!==false){
            $res['res']=count($goodsList);
            $res['msg']=$goodsList;
        }else{
            $res['res']=-1;
            $res['msg']=$goodsList;
        }
        echo json_encode($res);
        
    }
    
}