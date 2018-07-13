<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Public/Home/dist/ShareMember/show.css" />

    <title>会员专享</title>


</head>

<body>

    <div class="box" id="App">
        <img src="/Public/Assets/images/ShareMember/img.jpg" alt="">
        <a href="<?php echo ($href); ?>" class="f-btn">一键开通</a>
    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


    <script>
        var config = {
            m_api: '/index.php/Home/',
            c_api: '/index.php/Home/ShareMember/',
        }

        window['config'] = config;
    </script>

    <script src="/Public/Home/dist/ShareMember/show.js"></script>
</body>

</html>