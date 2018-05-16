<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月6日11:37:42
* 最新修改时间：2018年3月6日11:37:42
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####星级控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class StarController extends CommonController{
    
    //type 0 全部商品
    //type 1 选择商品
    
    public function getList(){
        
        $star=M('star');
        $result=$star->select();
        
        if($result){
            $res['res']=count($result);
            $res['count']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getGoodList(){
        
        $star_id=I('star_id');
        
        if(!$star_id){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        
        $where=[];
        $where['t1.star_id']=$star_id;
        $model=M();
        
        
        $result=$model
        ->table('c_star_goods as t1,c_goods as t2')
        ->order('t2.add_time desc')
        ->field('t1.*,t2.*')
        ->where($where)
        ->where('t1.goods_id = t2.goods_id')
        ->select();
        
        
        if($result){
            $res['res']=count($result);
            $res['count']=count($result);
            $res['msg']=$result;
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function add(){
        
        //type 0 全部商品
        //type 1 选择商品
        
        $model=M('star');
        $add=I('add','',false);
        if(!$add){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $star_id=getMd5('star');;
        $add['star_id']=$star_id;
        $add['add_time']=time();
        $add['edit_time']=time();
        
        
        $result=$model->add($add);
        
        if($result){
            
            $res['res']=1;
            
            // //type 0 全部商品
            // //type 1 选择商品
            // //初始化商品列表
            // $goods_list=[];
            // //初始化数组
            // $arr=[];
            
            // //如果是选择商品的类型
            // if($add['star_type']==1){
            //     //获得选择的商品列表
            //     $goods_list=I('goods_list');
            // }
            
            // //如果是全部商品的类型
            // if($add['star_type']==0){
            //     $model=M('goods');
            //     $goods_list=$model->field('goods_id')->select();
            // }
            
            
            // //创建星级商品模型
            // $model=M('star_goods');
            // //组装数据
            // foreach ($goods_list as $key => $value) {
            //     //初始化项
            //     $item=[];
            //     //设置星级id
            //     $item['star_id']=$star_id;
            //     //设置星级商品的id
            //     $item['goods_id']=$value['goods_id'];
            //     //追加到数组中
            //     $arr[]=$item;
            // }
            // //添加到数据库中
            // $result=$model->addAll($arr);
            
            
            
            
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function save(){
        $model=M('star');
        $save=I('save');
        $where=I('where');
        
        if(!$save || !$where){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $model=M('star');
        $where=I('where');
        $result=$model->where($where)->delete();
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function dels(){
        
        $model=M('star');
        $ids=I('ids');
        
        $where['star_id']=['in',$ids];
        
        $result=$model->where($where)->delete();
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
}