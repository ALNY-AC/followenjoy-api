<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年6月15日23:04:12
* 最新修改时间：2018年6月15日23:04:12
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####店铺控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class ShopController extends CommonController{
    
    
    //主
    public function getMoney(){
        
        $user_id=session('user_id');
        $Profit=D('Profit');
        $where=[];
        $where['user_id']=$user_id;
        $where['type']='收入';
        
        $money=$Profit->where($where)->sum('money');
        
        $res['res']=1;
        $res['msg']=$money;
        echo json_encode($res);
        
        
    }
    
    public function getVipCount(){
        $user_id=session('user_id');
        
        $UserSuper=D('UserSuper');
        $where=[];
        $where['super_id']=$user_id;
        $count=$UserSuper->where($where)->count();
        
        $res['res']=1;
        $res['msg']=$count;
        echo json_encode($res);
        
    }
    
    public function getToDayOrder(){
        
        $user_id=session('user_id');
        $shop_id=session('shop_id');
        
        $Order=D('Order');
        $where=[];
        // $where['share_id']=$user_id;
        $where['shop_id']=$shop_id;
        // $where['_logic'] = 'or';
        // $where['user_id']=['NEQ',$user_id];
        $lingTime=strtotime(date("Y-m-d"),time());//今天零点的时间
        $where['add_time'] = [['gt',$lingTime],['lt',time()]];//条件是，今天零点到当前时间
        $count=$Order->where($where)->count();
        
        // ===================================================================================
        // 再统计自己的
        $where=[];
        $where['user_id']=$user_id;
        $where['add_time'] = [['gt',$lingTime],['lt',time()]];//条件是，今天零点到当前时间
        $count+=$Order->where($where)->count();
        
        $res['res']=1;
        $res['msg']=$count;
        echo json_encode($res);
    }
    
    
    // 取得访问量
    public function getRecord(){
        
        $user_id=session('user_id');
        $shop_id=session('shop_id');
        
        $where=[];
        $where['shop_id']=$shop_id;
        
        $lingTime=strtotime(date("Y-m-d"),time());//今天零点的时间
        $where['add_time'] = [['gt',$lingTime],['lt',time()]];//条件是，今天零点到当前时间
        
        $Record=D('Record');
        $count=$Record
        ->where($where)
        ->group('goods_id')
        ->getField('goods_id',true);
        
        // dump($Record->_sql());
        // dump($count);
        
        $res['res']=1;
        $res['msg']=count($count);
        echo json_encode($res);
    }
    
    // 取客户总收入
    
    
}