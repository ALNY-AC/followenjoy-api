<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
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
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends CommonController{
    
    /**
    * 新增
    */
    public function creat(){
        
        $Goods=D('goods');
        
        $result=$Goods->creat(I('add','',false));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    //获得一个
    public function get(){
        
        $Goods=D('Goods');
        $goods_id=I('goods_id');
        
        if(I('map')){
            $goods=$Goods->get($goods_id,I('map'),I(''));
        }else{
            $goods=$Goods->get($goods_id);
        }
        
        if($goods){
            $res['res']=1;
            $res['msg']=$goods;
        }else{
            $res['res']=-1;
            $res['msg']=$goods;
        }
        echo json_encode($res);
        
    }
    
    //获得商品列表
    public function getList(){
        
        $Goods=D('Goods');
        
        $data=I();
        $data['where']=getKey();
        
        $class_id=$data['class_id'];
        
        if($class_id){
            $data['where']['goods_class']=$class_id;
        }
        
        $result=$Goods->getList($data);
        $res['count']=$Goods->where($data['where'])->count()+0;
        
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
        
        $Goods=D('Goods');
        $result=$Goods->getAll(I('ids'));
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    
    public function up(){
        
        $save=I('save','',false);
        $goods_id=$save['goods_id'];
        
        $Goods=M('goods');
        $goodsSave=[];
        $goodsSave['is_up']=$save['is_up'];
        
        $where=[];
        $where['goods_id']=$goods_id;
        $Goods->where($where)->save($goodsSave);
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    public function saveInfo(){
        
        $Goods=M('goods');
        
        $save=I('save','',false);
        unset($save['goods_id']);
        unset($save['add_time']);
        
        $where=I('where');
        $Goods->where($where)->save($save);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function saveData(){
        
        
        $Goods=D('Goods');
        
        $save=I('save','',false);
        $goods_id=I('goods_id');
        
        
        $Goods->addGoodsSku($goods_id,$save);
        $Goods->addGoodsImg($goods_id,$save);
        
        //保存商品
        $goodsSave=$save;
        
        unset($goodsSave['goods_id']);
        unset($goodsSave['add_time']);
        unset($goodsSave['sku']);
        unset($goodsSave['tree']);
        unset($goodsSave['img_list']);
        
        $goodsSave['edit_time']=time();
        
        $result=$Goods->saveData($goods_id,$goodsSave);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function del(){
        
        $goods_id=I('goods_id');
        $Goods=D('goods');
        $result=$Goods->del($goods_id);
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function printData(){
        $Goods=D('goods');
        $Goods->printData();
        
    }
    
    
    public function saveField(){
        $Goods=D('Goods');
        
        $data=I('data','',false);
        $goods_id=I('goods_id');
        
        unset($data['goods_id']);
        unset($data['add_time']);
        
        $data['edit_time']=time();
        
        
        $where=[];
        $where['goods_id']=$goods_id;
        $Goods->where($where)->save($data);
        
        if($result!==false){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getPreview(){   
        $Goods=D('Goods');
        $goods_id=I('goods_id');
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$Goods
        ->field([
        'goods_id',
        "goods_title",
        "sub_title",
        "goods_banner",
        "goods_content",
        "sort",
        "add_time",
        "edit_time",
        ])
        ->where($where)
        ->find();
        
        
        // ===================================================================================
        // 找图片
        $goods['img_list']=D('GoodsImg')->where($where)->getField('src',true);
        
        // ===================================================================================
        // 找sku
        $goods['sku']=D('Sku')->where($where)->select();
        
        if($goods){
            $res['res']=1;
            $res['msg']=$goods;
        }else{
            $res['res']=-1;
            $res['msg']=$goods;
        }
        
        echo json_encode($res);
    }
    
    
}