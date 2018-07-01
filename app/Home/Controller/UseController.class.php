<?php
namespace Home\Controller;
use Think\Controller;
class UseController extends CommonController {
    
    /**
    * 统一上传接口
    * 上传单个文件
    */
    public function upFile(){
        
        
        if (IS_POST) {
            $file = $_FILES['file'];
            if (!$file['error']) {
                //定义配置
                $cfg = [];
                //默认是管理上传路径
                //如果传了路径就使用传来的路径
                $path='';
                if(empty(I('post.src'))){
                    //默认路径
                    $cfg['rootPath']=WORKING_PATH . __UPLOAD__HOME__;
                    set_mkdir(WORKING_PATH . __UPLOAD__HOME__);
                    
                }else{
                    //传来的路径
                    $path=WORKING_PATH .'/Public/Upload/'.I('post.src');
                    
                    $cfg['rootPath']=   $path;
                    //创建目录
                    set_mkdir($path);
                    // $cfg['autoSub']=false;
                    // $cfg['hash']=false;
                    // $cfg['saveName']='';
                }
                
                if(!empty(I('post.del_src'))){
                    if(I('post.del_src')!==''){
                        
                        if(I('post.del_src')!=='/'){
                            
                            //删除
                            $src=WORKING_PATH.'/'.I('post.del_src');
                            $state=delFile($src);
                            
                        }
                        
                    }
                }
                // $cfg['exts']=array('jpg', 'gif', 'png', 'jpeg','mp4','wmv');//设置附件上传类型
                //实例化上传类
                $upload = new \Think\Upload($cfg);
                //开始上传
                $info = $upload -> uploadOne($file);
                //判断是否上传成功
                if ($info) {
                    //图片地址
                    if(empty(I('post.src'))){
                        //默认路径
                        $img_url = __ROOT__ . __UPLOAD__HOME__. $info['savepath'] . $info['savename'];
                    }else{
                        //传来的路径
                        $img_url = 'Public/Upload/' . I('post.src') . $info['savepath'] . $info['savename'];
                    }
                    
                    $result['res'] = 1;
                    $result['msg'] = [];
                    $result['msg']['info'] = '成功';
                    $result['msg']['src'] = $img_url;
                    $result['msg']['data'] = I('post.');
                    $result['msg']['file'] =$info;
                    
                } else {
                    $result['res'] = -1;
                    $result['msg'] = [];
                    $result['msg']['info'] = '失败，上传错误';
                    
                }
                
            } else {
                $result['res'] = -2;
                $result['msg'] = [];
                $result['msg']['info'] = '失败，文件错误';
                $result['msg']['file'] =$info;
                
            }
            echo json_encode($result);
        } else {
            echo '{"res":-1}';
        }
        
        
    }
    
    
    public function saveFile(){
        
        //=========输出json=========
        $image=I('file');
        //=========输出json=========
        
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $image, $result);
        
        $type = $result[2];    //获取图片的类型jpg png等
        $imageName = "25220_".date("His",time())."_".rand(1111,9999).".$type";
        
        
        if (strstr($image,",")){
            $image = explode(',',$image);
            $image = $image[1];
        }
        
        
        $path = "Public/Upload/home/dynamic/".date("Ymd",time());
        
        if (!is_dir($path)){ //判断目录是否存在 不存在就创建
            mkdir($path,0777,true);
        }
        
        $imageSrc=  $path."/". $imageName;
        
        $r = file_put_contents($imageSrc, base64_decode($image));
        
        $res=[];
        
        if (!$r) {
            $res['res']=-1;
        }else{
            $res['res']=1;
            $res['msg']=$imageSrc;
            
        }
        echo json_encode($res);
        
    }
    
    
}