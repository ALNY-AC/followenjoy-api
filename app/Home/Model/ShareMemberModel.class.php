<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月6日16:13:03
* 最新修改时间：2018年7月6日16:13:03
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分享会员模型#####
* @author 代码狮
*
*/
namespace Home\Model;
use Think\Model;
class ShareMemberModel extends Model {
    
    
    public function create($data=[]){
        
        $share_member_id=getMd5('share_member_id');
        $data["share_member_id"]=$share_member_id;
        $data["user_id"]=session('user_id');
        $data["add_time"]=time();
        $data["edit_time"]=time();
        
        if($this->add($data)){
            return $share_member_id;
        }else{
            return false;
        }
        
    }
    
    public function getUrl($share_member_id){
        
        $param=[];
        $param['share_member_id']=$share_member_id;
        $url=U('ShareMember/show',$param,'',true);
        return $url;
        
    }
    
    public function get($share_member_id){
        $where=[];
        $where['share_member_id']=$share_member_id;
        return $this->where($where)->find();
    }
    
    public function del($ids){
        $where=[];
        $where['share_member_id']=['in',getIds($ids)];
        return $this->where($where)->delete();
    }
    
    public function getList(){
        
        
    }
    
    public function saveData($ids,$data){
        
        $where=[];
        $where['share_member_id']=['in',getIds($ids)];
        return $this->where($where)->save($data);
        
    }
    
    public function isExpire($share_member_id){
        $where=[];
        $where['share_member_id']=$share_member_id;
        
        // ===================================================================================
        // 24小时过期
        $add_time=$this->where($where)->getField('add_time');
        
        // 86400
        if($add_time+1<time()){
            // 过期了
            return true;
        }else{
            return false;
        }
        
        
    }
    
    
    
    
}