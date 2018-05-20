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
class OrderController extends CommonController{
    
    public function creatPrintData(){
        
        $Order=D('Order');
        $download_id=$Order->creatPrintData();
        $res['res']=1;
        $res['msg']=$download_id;
        //组装url发送
        
        $url=U('printData',['download_id'=>$download_id],'',true);
        
        $res['url']=$url;
        
        echo json_encode($res);
    }
    public function printData(){
        $Order=D('Order');
        $Order->printData(I('get.download_id'));
    }
    //获得总数
    public function getCount(){
        $model=M('order');
        $count=$model->count();
        $res['res']=$count+0;
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
    }
    //获得列表
    public function getList(){
        
        $Order=D('Order');
        $data=I();
        $where=$data['where'];
        
        if($where['start_time']){
            $where['add_time'] = [['gt',$where['start_time']],['lt',$where['end_time']]];
            unset($where['start_time']);
            unset($where['end_time']);
        }
        
        
        $key=$data['key'];
        if($key){
            
            $keys=$key;
            //先根据空格分割为数组
            $keys = explode(" ", $keys);
            $keys = array_filter($keys);  // 删除空元素
            
            foreach ($keys as $k => $v) {
                $keys[$k]='%'.$v.'%';
            }
            $group=$data['group'];
            $where[$group]=['like',$keys,'OR'];
            
        }
        
        
        $data['where']=$where;
        
        $result=$Order->getList($data);
        
        $res['count']=$Order->where($where)->count()+0;
        
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    public function get(){
        $Order=D('Order');
        
        $result=$Order->get(I('order_id'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    //保存字段
    public function saveData(){
        
        $where=I('where');
        $Order=D('Order');
        $result=$Order->saveData(I('order_id'),I('save','',false));
        
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
        $Order=D('Order');
        
        $result=$Order->del(I('ids'));
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}