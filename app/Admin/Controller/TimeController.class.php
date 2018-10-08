<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月9日13:59:00
* 最新修改时间：2018年4月9日13:59:00
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####限时购控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class TimeController extends CommonController{
    
    
    public function create(){
        $Time=D('Time');
        $result=$Time->create(I('data'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function get(){
        $Time=D('Time');
        $result=$Time->get(I('time_id'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function getList(){
        $Time=D('Time');
        $data=I();
        $data['where']=getKey();
        $result=$Time->getList($data);
        $res['count']=$Time->where($data['where'])->count()+0;
        
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
        $Time=D('Time');
        $result=$Time->getAll(I());
        if($result!==false){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    public function saveData(){
        $Time=D('Time');
        $result=$Time->saveData(I('time_id'),I('data'));
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
        $Time=D('Time');
        $result=$Time->del(I('ids'));
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
    public function testRepeat(){
        
        
        $昨天0点=mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
        $昨天23点=mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
        
        
        $明天0点=mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
        $明天23点=mktime(23, 59, 59, date('m'), date('d')+1, date('Y'));
        
        $Model=D();
        
        $result=$Model->query("
        SELECT
        c_goods.goods_id,goods_title,
        src as goods_head,
        is_show,
        type,
        FROM_UNIXTIME(
        start_time,
        '%Y-%c-%d %h:%i:%s'
        ) AS start_time
        FROM
        c_time_goods
        LEFT JOIN c_goods ON c_time_goods.goods_id = c_goods.goods_id
        LEFT JOIN c_goods_img ON c_goods.goods_id = c_goods_img.goods_id
        WHERE
        start_time > $昨天0点
        AND start_time < $明天23点
        AND c_goods_img.slot = 0
        AND c_time_goods.goods_id IN (
        SELECT
        c_time_goods.goods_id
        FROM
        c_time_goods
        WHERE
        start_time > $昨天0点
        AND start_time < $明天23点
        GROUP BY
        c_time_goods.goods_id
        HAVING
        count(c_time_goods.goods_id) > 1
        )
        ORDER BY
        c_time_goods.goods_id;
        ");
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    public function testBanner(){
        $昨天0点=mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
        $昨天23点=mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
        
        
        $明天0点=mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
        $明天23点=mktime(23, 59, 59, date('m'), date('d')+1, date('Y'));
        $Model=D();
        
        $result=$Model->query("
        SELECT
        c_time_goods.goods_id,
        FROM_UNIXTIME(
        start_time,
        '%Y-%c-%d %h:%i:%s'
        ) AS start_time,
        src AS goods_head,
        goods_title
        FROM
        c_time_goods
        LEFT JOIN c_goods ON c_time_goods.goods_id = c_goods.goods_id
        LEFT JOIN c_goods_img ON c_time_goods.goods_id = c_goods_img.goods_id
        WHERE
        start_time > $昨天0点
        AND start_time < $明天23点
        AND goods_banner = ''
        AND c_goods_img.slot = 0;
        ");
        
        if($result){
            $res['res']=count($result);
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
        
    }
    
    public function testTotal(){
        
        $昨天0点=mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
        $昨天23点=mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
        
        
        $明天0点=mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
        $明天23点=mktime(23, 59, 59, date('m'), date('d')+1, date('Y'));
        $Model=D();
        
        $result=$Model->query("
        SELECT
        c_goods.goods_id,
        goods_title,
        SUM(stock_num_total) AS total,
        FROM_UNIXTIME(
        start_time,
        '%Y-%c-%d %h:%i:%s'
        ) AS start_time,
        src AS goods_head
        FROM
        c_sku
        LEFT JOIN c_time_goods ON c_time_goods.goods_id = c_sku.goods_id
        LEFT JOIN c_goods ON c_time_goods.goods_id = c_goods.goods_id
        LEFT JOIN c_goods_img ON c_time_goods.goods_id = c_goods_img.goods_id
        WHERE
        start_time > $昨天0点
        AND start_time < $明天23点
        AND c_goods_img.slot = 0
        AND type = 'kill'
        GROUP BY
        c_time_goods.goods_id
        ORDER BY
        start_time;
        ");
        
        $arr=[];
        foreach ($result as $k => $v) {
            
            if($v['total']<=0){
                $arr[]=$v;
            }
        }
        
        if($arr){
            $res['res']=count($arr);
            $res['msg']=$arr;
        }else{
            $res['res']=-1;
            $res['msg']=$arr;
        }
        echo json_encode($res);
        
    }
    
    
}