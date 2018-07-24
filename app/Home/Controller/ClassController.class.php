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
namespace Home\Controller;
use Think\Controller;
class ClassController extends Controller{
    
    
    //获得列表
    public function getList(){
        
        $model=M('Class');
        $where=I('where')?I('where'):[];
        
        $result=$model
        ->where($where)
        ->order('sort asc,add_time asc')
        ->select();
        
        
        // =========判断=========
        if($result){
            //总条数
            $result=toTime($result);
            
            
            $arr=[];
            
            
            
            foreach ($result as $key => $value) {
                
                $add=[];
                if(!$value['super_id']){
                    //没有super，代表是一级calss
                    $add=$value;
                    $add['node']=[];
                    $arr[]=$add;
                }
                if($value['super_id']){
                    //有super_id 代表是二级id，需要查找一级id，看看有没有一样的
                    for ($i=0; $i <count($arr) ; $i++) {
                        $class1=$arr[$i];
                        if($class1['class_id']==$value['super_id']){
                            $add=$value;
                            $arr[$i]['node'][]=$add;
                        }
                    }
                }
            }
            
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$arr;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    public function getAllRoot(){
        
        
        $Class=D('Class');
        $where=[];
        $where['super_id']=['EXP','is null'];
        $list=$Class
        ->order(
        [
        'class_id',
        'class_title',
        'img',
        'sort',
        ]
        )
        ->order('sort asc')
        ->where($where)
        ->select();
        if($list!==false){
            $res['res']=count($list);
            $res['msg']=$list;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    
    public function getAll(){
        
        $super_id=I('super_id');
        $Class=D('Class');
        $where=[];
        $where['super_id']=$super_id;
        
        $list=$Class
        ->order(
        [
        'class_id',
        'class_title',
        'img',
        'sort',
        ]
        )
        ->order('sort asc')
        ->where($where)
        ->select();
        if($list!==false){
            $res['res']=count($list);
            $res['msg']=$list;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
    }
    
    
    public function getGoodsList (){
        
        $class_id=I('class_id');
        $page=I('page');
        $page_size=I('page_size');
        
        $Goods=D('Goods');
        
        $where=[];
        $where['goods_class']=$class_id;
        
        $goodsList=$Goods
        ->where($where)
        ->field(
        [
        'goods_id',
        'goods_title',
        'goods_banner',
        'sub_title',
        ]
        )
        ->order('sort desc,add_time desc')
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        // ===================================================================================
        // 创建，模型
        $Sku=D('Sku');//sku的模型
        $GoodsImg=D('GoodsImg');//商品图片的模型
        
        // ===================================================================================
        // 找sku，但是只取一个
        foreach ($goodsList as $k => $v) {
            
            
            // ===================================================================================
            // sku
            $goods_id=$v['goods_id'];
            $sku=$Sku->getOne($goods_id);
            $v['sku']=$sku;
            
            // ===================================================================================
            // 商品的图片
            $img_list=$GoodsImg->getOne($goods_id);
            
            $v['img_list']=$img_list;
            $v['goods_head']=$img_list[0];
            $goodsList[$k]=$v;
        }
        
        
        // ===================================================================================
        // 取商品的图片
        
        if($goodsList!=false){
            $res['res']=count($goodsList);
            $res['msg']=$goodsList;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
}