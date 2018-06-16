<?php
namespace Admin\Model;
use Think\Model;
class DynamicModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($add){
        
        $dynamic_id=getMd5('dynamic');
        
        $this->saveImg($dynamic_id,$add['img_list']);
        unset($add['img_list']);
        
        $add['add_time']=time();
        $add['edit_time']=time();
        $add['dynamic_id']=$dynamic_id;
        
        //添加进去
        return $this->add($add);
        
    }
    
    public function getList($data){
        
        
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        
        $where=$data['where'];
        
        $dynamicList  =  $this
        ->order('release_time desc')
        ->field(
        [
        'dynamic_id',
        'user_id',
        'goods_id',
        'dynamic_title',
        'is_show',
        'add_time',
        'release_time',
        ]
        )
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $Img=M('dynamic_img');
        $Goods=D('goods');
        $User=M('user');
        
        for ($i=0; $i < count($dynamicList); $i++) {
            
            
            $dynamic_id=$dynamicList[$i]['dynamic_id'];
            
            //找图片
            // $dynamicList[$i]['img_list']=$this->getImgList($dynamic_id);
            //找商品
            $dynamicList[$i]['goods_info']=$this->getGoods($dynamicList[$i]['goods_id']);
            //找用户信息
            $dynamicList[$i]['user_info']=$this->getUserInfo($dynamicList[$i]['user_id']);
            
            $dynamicList[$i]['release_time']=date('Y-m-d H:i:s',$dynamicList[$i]['release_time']);
            
        }
        
        $dynamicList=  toTime($dynamicList);
        return $dynamicList;
        
    }
    
    private function getImgList($dynamic_id){
        $Img=M('dynamic_img');
        $where=[];
        $where['dynamic_id']=$dynamic_id;
        return $Img->order('sort asc')->where($where)->select();
        
    }
    
    private function getUserInfo($user_id){
        $User=D('user');
        $where=[];
        $where['user_id']=$user_id;
        return $User->field('user_name,user_head,user_id')->where($where)->find();
    }
    
    
    private function getGoods($goods_id){
        
        $Goods=D('Goods');
        $GoodsImg=D('GoodsImg');
        
        // ===================================================================================
        // 找商品
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$Goods
        ->where($where)
        ->field('goods_id,goods_title')
        ->find();
        // ===================================================================================
        // 找单图
        $img=$GoodsImg
        ->where($where)
        ->order('slot asc')
        ->getField('src');
        $goods['goods_head']=$img;
        
        return $goods;
    }
    
    public function get($dynamic_id){
        
        $Goods=D('goods');
        $where=I('where');
        $where['dynamic_id']=$dynamic_id;
        
        $dynamic=$this->where($where)->find();
        //找图片
        $dynamic['img_list']=$this->getImgList($dynamic_id);
        //找商品
        $dynamic['goods_info']=$this->getGoods($dynamic['goods_id']);
        //找用户信息
        $dynamic['user_info']=$this->getUserInfo($user_id);
        
        return $dynamic;
        
    }
    
    
    public function del($ids){
        
        $Img=M('dynamic_img');
        
        $where=I('where');
        $where['dynamic_id']=['in',$ids];
        
        $isDel= $this->where($where)->delete();
        $isImgDel=$Img->where($where)->delete();
        
        return $isDel && $isImgDel;
        
    }
    
    public function svaeData($dynamic_id,$save){
        
        
        $where=[];
        $where['dynamic_id']=$dynamic_id;
        
        // ===================================================================================
        // 构建图片数据
        $this->saveImg($dynamic_id,$save['img_list']);
        unset($save['img_list']);
        
        // ===================================================================================
        // 保存数据
        unset($save['add_time']);
        $save['edit_time']=time();
        //添加进去
        return $this->where($where)->save($save);
        
        
    }
    
    public function saveImg($dynamic_id,$imgList){
        
        $arr=[];
        
        foreach ($imgList as $k => $v) {
            
            $item=[];
            $item['dynamic_img_id']=getMd5('dynamic_img');
            $item['dynamic_id']=$dynamic_id;
            $item['src']=$v['src'];
            $item['sort']=$v['sort'];
            $item['add_time']=time();
            $item['edit_time']=time();
            
            $arr[]=$item;
        }
        // ===================================================================================
        // 创建模型
        $DynamicImg=D('DynamicImg');
        
        $where=[];
        $where['dynamic_id']=$dynamic_id;
        
        // ===================================================================================
        // 删除
        $DynamicImg->where($where)->delete();
        
        // ===================================================================================
        // 添加图片
        $DynamicImg->addAll($arr);
        
    }
    
}