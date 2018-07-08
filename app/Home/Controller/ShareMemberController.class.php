<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年7月6日16:12:36
* 最新修改时间：2018年7月6日16:12:36
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分享会员控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class ShareMemberController extends Controller{
    
    public function create(){
        if(!I('user_id')){
            die;
        }
        session('user_id',I('user_id'));
        $ShareMember=D('ShareMember');
        $share_member_id=$ShareMember->create();
        if($share_member_id){
            $url=$ShareMember->getUrl($share_member_id);
            $res['res']=1;
            $res['msg']=$url;
        }else{
            $res['res']=-1;
            $res['msg']='';
        }
        echo json_encode($res);
    }
    
    public function qrcode(){
        dump(I());
        // ===================================================================================
        // 图片生成
        
    }
    
    public function show(){
        $ShareMember=D('ShareMember');
        
        $share_member_id=I('share_member_id');
        // ===================================================================================
        // 判断是否过期
        $is=$ShareMember->isExpire($share_member_id);
        if(!$is){
            // 未过期
            // ===================================================================================
            // 取出分享人信息
            $share_id=I('share_id');
            $shop_id=I('shop_id');
            
            $href="http://q.followenjoy.cn/#/goodsInfo?goods_id=396&shop_id=$shop_id&share_id=$share_id";
            
            $this->assign('href',$href);
            $this->display();
        }else{
            // 已过期
            $this->display('expire');
            
        }
        
    }
    
}