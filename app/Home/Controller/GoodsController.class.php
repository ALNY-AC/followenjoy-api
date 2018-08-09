<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月2日11:17:23
* 最新修改时间：2018年3月2日11:17:23
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class GoodsController extends Controller{
    
    //取得详情页数据包
    public function getPacket(){
        
        
    }
    
    //搜索
    public function search(){
        
        $Goods=D('Goods');
        $list  =  $Goods->search($data);
        
        if($list!==false){
            $res['res']=count($list);
            $res['msg']=$list;
        }else{
            $res['res']=-1;
            $res['msg']=$list;
        }
        echo json_encode($res);
    }
    
    //获得商品列表
    public function getList(){
        
        
        $Goods=D('Goods');
        $data=I();
        $goodsList  =  $Goods->getList($data);
        // =========判断=========
        if($goodsList){
            //总条数
            $goodsList      =   toTime($goodsList);
            $res['res']     =   count($goodsList);
            $res['msg']     =   $goodsList;
            $res['count']   =   $Goods->count()+0;
            
        }else{
            $res['res']     =   0;
        }
        echo json_encode($res);
        
    }
    
    
    public function test(){
        $Goods=D('Goods');
        $data=I();
        $goodsList  =  $Goods->testGestList($data);
        // =========判断=========
        if($goodsList){
            //总条数
            $goodsList      =   toTime($goodsList);
            $res['res']     =   count($goodsList);
            $res['msg']     =   $goodsList;
            $res['count']   =   $Goods->count()+0;
            
        }else{
            $res['res']     =   0;
        }
        echo json_encode($res);
    }
    
    public function get(){
        
        $goods_id=I('goods_id');
        $type = I('activity_type')?I('activity_type'):'';
        if(!$goods_id){
            $res['res']=-2;
            $res['msg']='没有goods_id';
            echo json_encode($res);
            die;
        }
        
        $Goods=D('Goods');
        $TimeGoods=D('time_goods');
        $goods=$Goods->get($goods_id);
        $TimeGoods = $TimeGoods->where('goods_id ='.$goods_id. ' AND start_time<'.time() . ' AND end_time>'.time())->getField('end_time');
        if($goods){
            if($type == 'kill'){
                $goods['activity_type']['time_end_time'] = $TimeGoods;
                $goods['activity_type']['nowTime'] = time();
                $goods['activity_type']['activity_type'] = 'kill';
            }
            $res['res']=1;
            $res['msg']=$goods;
        }else{
            $res['res']=-1;
            $res['msg']=$goods;
        }
        echo json_encode($res);
        
    }
    
    public function query(){
        
    }
    
    
    public function show(){
        $goods_id=I('goods_id');
        $Goods=D('Goods');
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$Goods->where($where)->find();
        
        
        // dump($goods);
        
        
        // ===================================================================================
        // 取出图片
        
        $GoodsImg=D('GoodsImg');
        $goodsImg=$GoodsImg->where($where)->getField('src',true);
        $host='http://'.$_SERVER['SERVER_NAME'].'/';
        
        foreach ($goodsImg as $k => $v) {
            
            $v=$host.$v;
            $goodsImg[$k]=$v;
            
        }
        
        
        
        $this->assign('goodsImg',$goodsImg);
        $this->assign('goods',$goods);
        
        $this->display();
        
    }
    
    
}