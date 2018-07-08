<?php
namespace Admin\Controller;
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
                    $path=WORKING_PATH . __UPLOAD__ADMIN__;
                }else{
                    //传来的路径
                    $path=WORKING_PATH .'/Public/Upload/'.I('post.src');
                }
                
                
                $cfg['rootPath']=$path;//设置上传文件目录
                set_mkdir($path);//创建目录
                
                
                // 是否存在需要删除的文件
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
                    if(empty(I('post.src'))){
                        //默认路径
                        $img_url = __ROOT__ . __UPLOAD__ADMIN__. $info['savepath'] . $info['savename'];
                    }else{
                        //传来的路径
                        $img_url = '/Public/Upload/' . I('post.src') . $info['savepath'] . $info['savename'];
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
    
    
    public function upImage(){
        
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
                    $path=WORKING_PATH . __UPLOAD__ADMIN__;
                }else{
                    //传来的路径
                    $path=WORKING_PATH .'/Public/Upload/'.I('post.src');
                }
                
                
                $cfg['rootPath']=$path;//设置上传文件目录
                set_mkdir($path);//创建目录
                
                
                // 是否存在需要删除的文件
                if(!empty(I('post.del_src'))){
                    if(I('post.del_src')!==''){
                        if(I('post.del_src')!=='/'){
                            //删除
                            $src=WORKING_PATH.'/'.I('post.del_src');
                            $state=delFile($src);
                        }
                    }
                }
                
                
                $cfg['exts']=array('jpg', 'gif', 'png', 'jpeg');//设置附件上传类型
                
                
                //实例化上传类
                $upload = new \Think\Upload($cfg);
                
                //开始上传
                $info = $upload -> uploadOne($file);
                
                
                //判断是否上传成功
                if ($info) {
                    
                    if(empty(I('post.src'))){
                        //默认路径
                        $img_url = __ROOT__ . __UPLOAD__ADMIN__. $info['savepath'] . $info['savename'];
                    }else{
                        //传来的路径
                        $img_url = '/Public/Upload/' . I('post.src') . $info['savepath'] . $info['savename'];
                    }
                    
                    // 压缩实现
                    if($info['size']>128000){
                        
                        $image = new \Think\Image();
                        $image->open('.'.$img_url);
                        // $width = $image->width(); // 返回图片的宽度$height = $image->height(); // 返回图片的高度
                        // $height = $image->height(); // 返回图片的高度
                        $image->save('.'.$img_url,null,80);
                        
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
    
}