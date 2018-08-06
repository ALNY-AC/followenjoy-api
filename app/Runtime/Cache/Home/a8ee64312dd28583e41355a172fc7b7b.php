<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>分享红包</title>
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/Public/Home/dist/ShareRed/ShareRed.css" />

    <style>
    </style>
</head>

<body>

    <div class="header">
        <div class="img-mask"></div>
        <img class="header-bg" src="/Public/Assets/images/red/share_img_title.png" alt="">
    </div>

    <div class="red-card-panel hide">

        <div class="red-head">
            <img src="/Public/Assets/images/red/share_bg_money1.png" alt="">
            <div class="red-head-info">
                <div class="red-head-info-box">
                    <div class="title">拼手气红包</div>
                    <div class="price">0.63
                        <span class="price-icon">元</span>
                    </div>
                    <div class="time">红包有效期至：2018.08.08</div>
                </div>
            </div>
        </div>
        <div class="red-body">
            <p>红包已放至账户
                <span class="phone">13914896237</span>
            </p>
            <p>登录App即可使用</p>
            <p>下载App即可使用50元新人红包</p>
        </div>
        <div class="red-footer">
            <a href="">
                <img src="/Public/Assets/images/red/share_btn_openapp.png" alt="">
            </a>
        </div>

    </div>



    <div id="App" class="hside">
        <div class="app-body" :style="[{opacity:opacity}]">

            <div class="input-panel" v-if="!isLogin()">
                <input type="tel" v-model="user_phone" placeholder="请输入您的手机号" class="input">
                <div class="login-btn" @click="setPhone()">立即领取</div>
            </div>

            <div class="coupon-panel coupon-header hide" v-if="isLogin()">
                <div class="coupon-panel-header">
                </div>
                <div class="coupon-panel-body">
                    <div class="coupon-list">
                        <coupon-card v-if="myRecord && !is_max" title="拼手气红包" :info="myRecord.info" :price="myRecord.price"></coupon-card>
                        <coupon-card v-if="myRecord && is_max" title="拼手气红包" :info="myRecord.info" price="5"></coupon-card>
                    </div>
                    <div class="high-title fff">- 手气一般没关系，还有优惠送给你 -</div>
                    <!-- <div class="coupon-list"> -->
                    <!-- <coupon-card :title="coupon.title" :info="coupon.info" :price="coupon.price" v-for="(coupon,i) in giveCouponList" :key="i"></coupon-card> -->
                    <!-- </div> -->
                </div>
                <div class="coupon-panel-footer"></div>
            </div>


            <div class="red-card-panel" v-if="isLogin()">

                <div class="red-head">
                    <img src="/Public/Assets/images/red/share_bg_money1.png" alt="">
                    <div class="red-head-info">
                        <div class="red-head-info-box">
                            <div class="title">拼手气红包</div>
                            <div class="price">{{myRecord.price}}
                                <span class="price-icon">元</span>
                            </div>
                            <div class="time">红包有效期至：{{myRecord.time}}</div>
                        </div>
                    </div>
                </div>
                <div class="red-body">
                    <p>红包已放至账户
                        <span class="phone" @click="clearPhone()">{{phone}}</span>
                    </p>
                    <p>登录App即可使用</p>
                    <p>下载App即可使用50元新人红包</p>
                </div>
                <div class="red-footer">
                    <a href="">
                        <img src="/Public/Assets/images/red/share_btn_openapp.png" alt="">
                    </a>
                </div>

            </div>

            <div class="high-title red" v-if="isLogin()">
                <div class="title">看看朋友们手气如何</div>
            </div>

            <div class="user-list" v-if="isLogin()">
                <user-card :is-up="user.is_up" :price="user.price" :name="user.nickname" :info="user.info" :time="user.add_time" :img="user.headimgurl"
                    v-for="(user,i) in recordList" :key="i">
                </user-card>
                <div class="user-list-footer" v-if="!is_max">
                    只差一点点，大红包就是你的啦 ~
                </div>
            </div>

        </div>


    </div>

    <script type="text/x-template" id="coupon-card">

        <div class="coupon-card">
                <div class="coupon-card-body">
                    <div class="coupon-price">
                        <span class="icon">￥</span>
                        <span class="price">{{price}}</span>
                    </div>
                    <div class="coupon-info">
                        <div class="coupon-info-body">
                            <div class="title">{{title}}</div>
                            <ul class="info-list">
                                <li v-for="(item,i) in info" :key="i">· {{item}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="coupon-btn-box">
                        <div class="coupon-btn" @click.stop="go()">
                            立即使用
                        </div>
                    </div>
                </div>
            </div>

      </script>
    <script type="text/x-template" id="user-card">

        <div class="user-card">
            <div class="user-header">
                <img :src="img" alt="">
            </div>
            <div class="user-info-box">
                <div class="user-info-row">
                    <div class="user-name">{{name}}</div>
                    <div class="user-time">{{time}}</div>
                </div>
                <div class="user-info-row">
                    <div class="user-info clearfix">{{info}}</div>
                </div>
            </div>
            <div class="user-price-box">
                <div class="user-price">{{price}} 元</div>
                <span class="pull-right" v-if="isUp">
                        <i class="fa fa-thumbs-o-up"></i>
                        <span>手气最佳</span>
                    </span>
            </div>
        </div>

      </script>

    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script>
        var config = {
            m_api: '/index.php/Home/',
            c_api: '/index.php/Home/ShareRed/',
            share_red_id: '<?php echo ($share_red_id); ?>',
            is_max: '<?php echo ($is_max); ?>',
            recordList: JSON.parse('<?php echo ($recordList); ?>'),
            myRecord: JSON.parse('<?php echo ($myRecord); ?>'),
        }

        console.warn(config);
        window['config'] = config;

    </script>

    <script src="/Public/Home/dist/ShareRed/ShareRed.js"></script>



</body>

</html>