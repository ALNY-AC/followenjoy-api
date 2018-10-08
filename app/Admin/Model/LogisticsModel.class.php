<?php
namespace Admin\Model;
use Think\Model;
class LogisticsModel extends Model {
    
    
    public function getInfo($data){
        $post_data = array();
        $post_data["customer"] = '86545A2CEDCDC0A43AF74F4870B13815';
        $key= 'YmFAdKBv452' ;
        
        $param=[];
        $param['com']=$data['com'];
        $param['num']=$data['num'];
        
        $post_data["param"] =json_encode($param);
        $url='http://poll.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o="";
        foreach ($post_data as $k=>$v){
            $o.= "$k=".urlencode($v)."&"; //默认UTF-8编码格式
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        
        $data = str_replace("\"",'"',$result );
        $data = json_decode($data,true);
        
        if(isset($data['data'])){
        }else{
            $data['data']=[];
        }
        
        $info=[
        [
        "time" =>  "",
        "ftime" =>  "",
        "context" =>  "订单正在处理",
        ],
        [
        "time" =>  "",
        "ftime" =>  "",
        "context" =>  "物流已揽件，请您耐心等待发货",
        ],
        ];
        $data['data']=array_merge(  $data['data'],$info);
        
        return $data;
    }
    
    
    
    public function saveData($order_id,$save){
        $where=[];
        $where['order_id']=$order_id;
        $save['edit_time']=time();
        
        if($save['logistics_number']){
            $Order=D('Order');
            $order=$Order->where($where)->find();
            
            if($order['state']=='2'){
                $oSave=[];
                $oSave['state']=3;
                $Order->where($where)->save($oSave);
            }
            
        }
        
        return $this->where($where)->save($save);
    }
    
    
}