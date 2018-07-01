<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />

    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/Public/Admin/dist/loginPage/loginPage.css" />


    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>随享季后台登录</title>
</head>

<body>


    <div id="App">

        <div class="weixin-info" :style="{opacity:opacity}" v-if="config">
            <div class="weixin-head">
                <img :src="weixinInfo.headimgurl" alt="">
            </div>
            <div class="weixin-name text-muted">
                {{weixinInfo.nickname}}
            </div>

            <div class="weixin-login" v-if="config.isBinding=='0'">
                <div class="weixin-login-title text-muted">第一次登录需要绑定到账户</div>
                <div class="weixin-login-body">
                    <div class="form-group">
                        <input type="text" v-model="data.admin_id" class="form-control input-lg" placeholder="请输入账户">
                    </div>
                    <div class="form-group">
                        <input type="password" v-model="data.admin_pwd" class="form-control input-lg" placeholder="请输入密码">
                    </div>
                </div>
            </div>
            <div class="weixin-login" v-else>
                <div class="weixin-login-title text-muted">确认登录这个的账户吗？</div>
                <div class="weixin-login-body">
                    <p class="text-muted">账户：{{config.adminInfo.admin_id}}</p>
                    <p class="text-muted">昵称：{{config.adminInfo.admin_name}}</p>
                </div>
            </div>


            <div class="weixin-btn">
                <button class="btn btn-success btn-lg btn-block" @click="login" v-if="config.isBinding=='0'">确认绑定</button>
                <button class="btn btn-success btn-lg btn-block" @click="loginAdmin" v-else>{{btnTitle}}</button>
            </div>

            <!--  -->


        </div>


    </div>

    <script>

        var config = {
            m_api: '/index.php/Admin/',
            c_api: '/index.php/Admin/Login/',
            token: '<?php echo ($token); ?>',
            qrcode_id: '<?php echo ($qrcode_id); ?>',
            isBinding: eval('<?php echo ($isBinding); ?>'),
            weixinInfo: JSON.parse('<?php echo ($weixinInfo); ?>'),
            adminInfo: JSON.parse('<?php echo ($adminInfo); ?>'),
        }
        console.warn(config);

        window['config'] = config;

    </script>

    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



    <script src="/Public/Admin/dist/loginPage/loginPage.js"></script>


</body>

</html>