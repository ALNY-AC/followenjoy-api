(function ($, Vue, config) {
    // 先设置rem

    $("html").css("font-size", window.innerWidth / 10);
    $(window).resize(function () {
        $("html").css("font-size", window.innerWidth / 10);
    });

    // $(document).on('click', function () {
    //     location.reload();
    // });

    // http://server.followenjoy.cn/index.php/Home/ShareRed/show/share_red_id/a1d4df7f8ded4e379bf0e2da9687973f

    Vue.component('coupon-card', {
        template: '#coupon-card',
        props: {
            price: {
                type: String,
                default: '',
            },
            title: {
                type: String,
                default: '',
            },
            info: {
                type: Array,
                default() {
                    return [];
                },
            },
        },
        data() {
            return {};
        },
        methods: {
            go() {
                window.location.href = "http://q.followenjoy.cn/#/HomePage";
            }
        },
        computed: {},
        //过滤器
        filters: {},
        mounted() { this.$nextTick(() => { }) },
        //Vue 实例销毁后调用
        destroyed() { },
        components: {},
        watch: {}
    })

    Vue.component('user-card', {
        template: '#user-card',
        props: {
            price: {
                type: String,
                default: '',
            },
            name: {
                type: String,
                default: '',
            },
            info: {
                type: String,
                default: '',
            },
            time: {
                type: String,
                default: '',
            },
            img: {
                type: String,
                default: '',
            },
            isUp: {
                type: Boolean,
                default: '',
            }
        },
        data() {
            return {};
        },
        methods: {

        },
        computed: {},
        //过滤器
        filters: {},
        mounted() { this.$nextTick(() => { }) },
        //Vue 实例销毁后调用
        destroyed() { },
        components: {},
        watch: {}
    })


    var App = new Vue({
        el: "#App",
        data() {
            return {
                opacity: 0,
                couponList: [
                    {
                        title: '拼手气红包',
                        price: '3',
                        info: [
                            '满30元可用',
                            '2018-06-21到期'
                        ]
                    },
                ],
                giveCouponList: [
                    {
                        title: '拼手气红包',
                        price: '3',
                        info: [
                            '满30元可用',
                            '2018-06-21到期'
                        ]
                    }, {
                        title: '拼手气红包',
                        price: '3',
                        info: [
                            '满30元可用',
                            '2018-06-21到期'
                        ]
                    },
                ],
                recordList: [],

                // [
                //     {
                //         record_id: '',
                //         index: '',
                //         share_red_id: '',
                //         user_id: '',
                //         price: '',
                //         unionid: '',
                //         nickname: '',
                //         headimgurl: '',
                //         dev_value: '',
                //         add_time: '',
                //         edit_time: '',
                //     },
                //     {
                //         price: '3',
                //         name: '某某某',
                //         info: '红包领的好，吃饭没烦恼',
                //         time: '06-21 15:31:32',
                //         img: 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIDQiaDnBtoKiax02W79918KWLP59sYHo3yV72a0NPY7ibksSYQcLgMHT3gmqu5b7N2pVu89mTJeDgfA/132',
                //     },
                // ],
                user_phone: '',
                is_max: false,
                myRecord: null,
            };
        },
        methods: {
            isLogin() {
                return localStorage['phone'] != undefined && localStorage['phone'] != null && localStorage['phone'].length > 0
            },
            setPhone() {
                if (this.user_phone.length < 11) {
                    alert('手机号输入有误！');
                    return;
                }
                localStorage['phone'] = this.user_phone;
                this.pull();
                setTimeout(() => {
                    this.user_phone = '';
                }, 100);
            },
            clearPhone() {
                function reload() {
                    window.location.href = window.location.href + "?test_id=" + 10000 * Math.random();
                }
                if (confirm('确认重新输入手机号吗？')) {
                    localStorage.removeItem('phone');
                    reload();
                }
            },
            pull() {

                $.get(config.c_api + 'pull', {
                    phone: this.phone,
                    share_red_id: this.share_red_id
                }, res => {
                    res = JSON.parse(res);
                    if (res.res >= 1) {
                        function reload() {
                            window.location.href = window.location.href + "?test_id=" + 10000 * Math.random();
                        }
                        reload();
                    }
                    if (res.res == -2) {
                        alert('红包已发完~');
                    }
                    if (res.res == -3) {
                        // alert('您已经领取过了哦~');
                        console.warn('您已经领取过了哦~');

                    }
                });

            }
        },
        mounted: function () {

            this.share_red_id = config.share_red_id;
            this.recordList = config.recordList;
            this.is_max = config.is_max;
            this.myRecord = config.myRecord;

            this.$nextTick(function () {
                this.opacity = 1;
                if (this.isLogin()) {
                    // 立即领取
                    this.pull();
                } else {
                    // 等待输入手机号后再次领取
                }
            });
        },
        components: {

        },
        watch: {},
        computed: {
            phone() {
                if (this.isLogin()) {
                    return localStorage['phone'];
                } else {
                    return '';
                }
            },
            isUp() {
                return config.is_up;
            }
        },
        //过滤器
        filters: {},
        //Vue 实例销毁后调用
        destroyed() { },
    });






}).call(this, this['$'], this['Vue'], this['config']);