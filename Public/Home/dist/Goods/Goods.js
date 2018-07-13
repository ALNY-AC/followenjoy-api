(function ($, Vue, config, Swiper) {


    let App = new Vue({
        el: '#App',
        data() {
            return {
                message: 'Hello Word',
            };
        },
        methods: {

        },
        mounted() {

        },
        components: {},
        watch: {},
    });

    var mySwiper = new Swiper('.swiper-container', {
        autoplay: true,//可选选项，自动滑动
    })

}).call(this, this['$'], this['Vue'], this['config'], this['Swiper']);
