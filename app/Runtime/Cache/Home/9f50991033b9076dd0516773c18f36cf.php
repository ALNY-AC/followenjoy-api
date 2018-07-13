<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>商品详情页</title>
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/dist/Goods/Goods.css" />
</head>

<body>

    <div id="App">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(is_array($goodsImg)): $i = 0; $__LIST__ = $goodsImg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                        <img src="<?php echo ($img); ?>" alt="">
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>

            </div>
        </div>

        <div class="goods-info-head-card">
            <div class="title-row">

                <div class="goods-title">
                    <span><?php echo ($goods["goods_title"]); ?></span>
                </div>
                <div class="goods-like">
                    <i class="fa fa-heart-o"></i>
                    <div>未收藏</div>
                </div>

            </div>
            <div class="goods-sub-title">
                <span><?php echo ($goods["sub_title"]); ?></span>
            </div>

            <div class="goods-price-box">
                <div class="goods-price">
                    999/
                    <span class="z">赚99</span>
                </div>
            </div>
        </div>

        <div class="goods-content">
            <?php echo ($goods["goods_content"]); ?>
        </div>


    </div>

    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>

    <script src="/Public/Home/dist/Goods/Goods.js"></script>


</body>

</html>