<?php
namespace Home\Model;
use Think\Model;
class DynamicModel extends Model {
    
    
    public function _initialize (){}
    
    
    public function getList($data){
        
        
        $page   =   $data['page']?$data['page']:1;
        $page_size  =   $data['page_size']?$data['page_size']:10;
        $where  =   $data['where']?$data['where']:[];
        
        $where['is_show']= 1;
        $where['release_time']= ['ELT',time()];//当前时间到达发布时间
        
        $dynamicList  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$page_size,$page_size)
        ->select();
        
        $Img=M('dynamic_img');
        $Goods=D('goods');
        $GoodsImg=D('GoodsImg');
        $User=M('user');
        
        foreach ($dynamicList as $k => $v) {
            $dynamic_id=$v['dynamic_id'];
            // ===================================================================================
            // 找图片
            $where=[];
            $where['dynamic_id']=$dynamic_id;
            $img_list=$Img
            // ->field('')
            ->order('sort asc')
            ->where($where)
            ->select();
            $v['img_list']=$img_list;
            
            // ===================================================================================
            // 找商品
            $where=[];
            $where['goods_id']=$v['goods_id'];
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
            $v['goods_info']=$goods;
            
            // ===================================================================================
            // 找用户信息
            $user_id=$v['user_id'];
            $where=[];
            $where['user_id']=$user_id;
            $user=$User
            ->field('user_name,user_head,user_id')
            ->where($where)
            ->find();
            $v['user_info']=$user;
            
            $dynamicList[$k]=$v;
        }
        
        $dynamicList=to_format_date($dynamicList,'release_time');
        
        return $dynamicList;
    }
    
    public function getFollowList($data){
        
        //先取得关注人的列表
        $Follow=M('follow');
        
        $where=[];
        $where['user_id']=session('user_id');
        $userList=$Follow->where($where)->select();
        
        $ids=[];
        for ($i=0; $i < count($userList); $i++) {
            $ids[]=$userList[$i]['follow_id'];
        }
        $page   =   $data['page']?$data['page']:1;
        $limit  =   $data['limit']?$data['limit']:10;
        
        if(count($ids)<=0){
            return [];
        }
        $where=[];
        $where['user_id']= ['in',$ids];
        $where['is_show']= 1;
        
        //找列表
        $dynamicList  =  $this
        ->order('add_time desc')
        ->where($where)
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        $Img=M('dynamic_img');
        $Goods=D('goods');
        $User=M('user');
        $Follow=M('follow');
        for ($i=0; $i < count($dynamicList); $i++) {
            //找图片
            $dynamic_id=$dynamicList[$i]['dynamic_id'];
            $where=[];
            $where['dynamic_id']=$dynamic_id;
            $img_list=$Img->order('sort asc')->where($where)->select();
            $dynamicList[$i]['img_list']=$img_list;
            
            //找商品
            $goods=$Goods->get($dynamicList[$i]['goods_id']);
            $dynamicList[$i]['goods_info']=$goods;
            
            //找用户信息
            $user_id=$dynamicList[$i]['user_id'];
            $where=[];
            $where['user_id']=$user_id;
            $user=$User->where($where)->find();
            $dynamicList[$i]['user_info']=$user;
            
            
            //检查是否关注
            $my_user_id=session('user_id');
            $where=[];
            $where['user_id']=$my_user_id;
            $where['follow_id']=$user_id;
            
            $is_follow=$Follow->where($where)->find()!=null;
            $dynamicList[$i]['is_follow']=$is_follow;
            
            
        }
        
        return $dynamicList;
        
        
    }
    
    //获得一个
    public function get($goods_id,$map=['img_list','sku','tree']){
        
        $where=[];
        $where['is_up']=1;
        $where['goods_id']=$goods_id;
        
        $goods=$this->where($where)->find();
        
        $goods=getGoodsSku($goods,$map);
        $goods=toTime([$goods])[0];
        //找是否收藏
        $model=M('collection');
        $where=[];
        $where['goods_id']=$goods_id;
        $where['user_id']=session('user_id');
        $collection=$model->where($where)->find();
        
        $goods['is_collection']=!($collection==null);
        
        
        return $goods;
        
    }
    
    
}