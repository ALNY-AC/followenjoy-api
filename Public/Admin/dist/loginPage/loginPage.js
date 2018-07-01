(function ($, Vue, config) {
    // 先设置rem

    $(document).on('click', function () {
        // location.reload();
    });
    var App = new Vue({
        el: "#App",
        data() {
            return {
                weixinInfo: {
                    unionid: '',
                    nickname: '',
                    headimgurl: '',
                },
                config: null,
                opacity: 0,
                data: {
                    admin_id: '',
                    admin_pwd: '',
                    unionid: '',
                    qrcode_id: '',
                },
                btnTitle: '确认登录',
            };
        },
        methods: {
            login() {
                this.data.unionid = this.weixinInfo.unionid;
                this.data.qrcode_id = this.config.qrcode_id;
                $.get(config.c_api + 'setPhone', this.data, res => {
                    res = JSON.parse(res);
                    if (res.res >= 1) {
                        location.reload();
                    }
                });
            },
            loginAdmin() {

                if (this.btnTitle == '登录成功~点击关闭页面') {
                    WeixinJSBridge.invoke('closeWindow', {}, function (res) { });
                    return;
                }

                this.data.unionid = this.weixinInfo.unionid;
                this.data.qrcode_id = this.config.qrcode_id;
                $.get(config.c_api + 'setLoginState', this.data, res => {
                    res = JSON.parse(res);
                    if (res.res >= 1) {
                        this.btnTitle = '登录成功~点击关闭页面';
                    } else {
                        this.btnTitle = '失败，请重试或使用账户密码登录~';
                    }
                });

            }
        },
        mounted: function () {

            this.weixinInfo = config.weixinInfo;
            this.config = config;
            this.$nextTick(() => {
                this.opacity = 1;
            });

        },
        components: {

        },
        watch: {},
        computed: {

        },
        //过滤器
        filters: {},
        //Vue 实例销毁后调用
        destroyed() { },
    });


}).call(this, this['$'], this['Vue'], this['config']);