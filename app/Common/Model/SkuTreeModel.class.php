<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年5月25日15:34:24
* 最新修改时间：2018年5月25日15:34:24
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####sku的树模型#####
* @author 代码狮
*
*/
namespace Common\Model;
use Think\Model;
class SkuTreeModel extends Model {
    
    private $SkuTreeV;
    
    public function _initialize (){
        $this->SkuTreeV=D('SkuTreeV');
    }
    /**
    * 取得sku树
    */
    public function getTree($goods_id){
        
        $where=[];
        $where['goods_id']=$goods_id;
        $tree= $this
        ->where($where)
        ->order('k_s asc')
        ->field(
        [
        'sku_tree_id',
        // 'goods_id',
        'k',
        'k_s',
        // 'add_time',
        // 'edit_time',
        ]
        )
        ->select();
        
        foreach ($tree as $k => $v) {
            $sku_tree_id=$v['sku_tree_id'];
            $where['sku_tree_id']=$sku_tree_id;
            $s_v=$this
            ->SkuTreeV
            ->field(
            [
            'v_id',
            // 'goods_id',
            'sku_tree_id',
            'id',
            'name',
            'img_url',
            // 'add_time',
            // 'edit_time',
            ]
            )
            ->where($where)
            ->select();
            $v['v']= $s_v;
            $tree[$k]= $v;
        }
        return $tree;
    }
    
  
    
    
    
}