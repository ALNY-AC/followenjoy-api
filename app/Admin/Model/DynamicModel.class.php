<?php
namespace Admin\Model;
use Think\Model;
class DynamicModel extends Model {
    
    
    public function _initialize (){}
    
    public function creat($add){
        $dynamic_id=getMd5('dynamic');
        $imgList=$add['img_list'];
        unset($add['img_list']);
        
        $img_list=[];
        for ($i=0; $i < count($imgList); $i++) {
            
            $add['dynamic_img_id']=getMd5('dynamic_img');
            $add['dynamic_id']=$dynamic_id;
            $add['src']=$imgList[$i];
            $add['add_time']=time();
            $add['edit_time']=time();
            $img_list[]=$add;
        }
        
        $DynamicImg=M('dynamic_img');
        $DynamicImg->addAll($img_list);
        
        //设置用户id
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
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $Img=M('dynamic_img');
        $Goods=D('goods');
        $User=M('user');
        for ($i=0; $i < count($dynamicList); $i++) {
            
            
            $dynamic_id=$dynamicList[$i]['dynamic_id'];
            
            //找图片
            $dynamicList[$i]['img_list']=$this->getImgList($dynamic_id);
            
            
            //找商品
            $dynamicList[$i]['goods_info']=$Goods->get($dynamicList[$i]['goods_id']);
            //找用户信息
            $dynamicList[$i]['user_info']=$this->getUserInfo($dynamicList[$i]['user_id']);
            
            
        }
        
        $dynamicList=  toTime($dynamicList);
        
        return $dynamicList;
        
    }
    
    private function getImgList($dynamic_id){
        
        $Img=M('dynamic_img');
        $where=[];
        $where['dynamic_id']=$dynamic_id;
        return $Img->where($where)->select();
        
    }
    
    private function getUserInfo($user_id){
        $User=D('user');
        $where=[];
        $where['user_id']=$user_id;
        return $User->where($where)->find();
    }
    
    public function get($dynamic_id){
        
        $Goods=D('goods');
        $where=I('where');
        $where['dynamic_id']=$dynamic_id;
        
        $dynamic=$this->where($where)->find();
        //找图片
        $dynamic['img_list']=$this->getImgList($dynamic_id);
        //找商品
        $dynamic['goods_info']=$Goods->get($dynamic['goods_id']);
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
        // 创建模型
        $DynamicImg=M('dynamic_img');
        
        // ===================================================================================
        // 先删除图片
        $DynamicImg->where($where)->delete();
        
        
        // ===================================================================================
        // 构建图片数据
        $imgList=$save['img_list'];
        unset($save['img_list']);
        $img_list=[];
        for ($i=0; $i < count($imgList); $i++) {
            $save['dynamic_img_id']=getMd5('dynamic_img');
            $save['dynamic_id']=$dynamic_id;
            $save['src']=$imgList[$i];
            $save['add_time']=time();
            $save['edit_time']=time();
            $img_list[]=$save;
        }
        
        // ===================================================================================
        // 添加图片
        $DynamicImg->addAll($img_list);
        
        // ===================================================================================
        // 保存数据
        unset($save['add_time']);
        $save['edit_time']=time();
        //添加进去
        return $this->where($where)->save($save);
        
        
    }
    
}