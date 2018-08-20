<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日18:50:25
* 最新修改时间：2018年4月8日18:50:25
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
namespace Admin\Controller;
use Think\Controller;
class SpecialController extends CommonController{
    
    public function get(){
        $special_id=I('special_id');
        $Special=D('Special');
        $result=$Special->get($special_id);
        
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
        
        $Special=D('Special');
        $result=$Special->getList(I());
        $res['count']=$Special->where(I('where'))->count()+0;
        
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
            $res['I']=I();
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
    public function getAll(){
        
        $Special=D('Special');
        $result=$Special
        ->field('')
        ->order('sort asc')
        ->select();
        
        $result=toTime($result,'Y-m-d H:i:s',['edit_time']);
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    public function creat(){
        
        
        $Special=D('Special');
        $result=$Special->creat(I('add'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function saveData(){
        
        $Special=D('Special');
        $result=$Special->saveData(I('special_id'),I('save'));
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    // ===================================================================================
    // 文章操作
    
    
    public function addPaper(){
        
        $Special=D('Special');
        $result=$Special->addPaper(I('special_id'),I('special_paper_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    
    // ===================================================================================
    // 商品操作
    
    public function addGoods($special_id,$goods_id){
        $Special=D('Special');
        $result=$Special->addGoods(I('special_id'),I('goods_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    //删除商品
    public function delGoods(){
        
        $Special=D('Special');
        $result=$Special->delGoods(I('special_id'),I('goods_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        $Special=D('Special');
        $result=$Special->del(I('ids'));
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
        
        $SpecialGoods=D('SpecialGoods');
        $Goods=D('Goods');
        
        $special_id=I('special_id');
        $page=I('page');
        $page_size=I('page_size');
        
        $goodsList=$SpecialGoods
        ->distinct(true)
        ->table('c_special_goods as t1,c_goods as t2')
        ->field('t1.*,t2.goods_id,t2.goods_title,t2.goods_banner,t2.sub_title,t2.sort,t2.add_time,t2.is_up')
        ->order('t2.sort desc,t2.add_time desc')
        ->where("t1.special_id='$special_id' AND t1.goods_id = t2.goods_id")
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $where=[];
        $where['special_id']=$special_id;
        $res['total']=$SpecialGoods
        ->where($where)
        ->count()+0;
        
        $where=[];
        $where['goods_id']=['in',getIds($ids)];
        
        
        $GoodsImg=D('GoodsImg');
        $Sku=D('sku');
        foreach ($goodsList as $k => $v) {
            $where=[];
            $where['goods_id']=$v['goods_id'];
            $v['goods_head']=$GoodsImg->order('slot asc')->where($where)->getField('src');
            $v['price']=$Sku->where($where)->getField('price');
            $v['stock_num']=$Sku->where($where)->sum('stock_num');
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
    
    public function addGoodsPlus(){
        $SpecialGoods=D('SpecialGoods');
        $data=[];
        $data['special_id']=I('special_id');
        $data['goods_id']=I('goods_id');
        
        $where=[];
        $where['special_id']=I('special_id');
        $where['goods_id']=['in',getIds(I('goods_id'))];
        $SpecialGoods->where($where)->delete();
        
        
        $result=$SpecialGoods->add($data,null,true);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function delGoodsPlus(){
        
        $SpecialGoods=D('SpecialGoods');
        
        $where=[];
        $where['special_id']=I('special_id');
        $where['goods_id']=['in',getIds(I('goods_id'))];
        $result=$SpecialGoods->where($where)->delete();
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}