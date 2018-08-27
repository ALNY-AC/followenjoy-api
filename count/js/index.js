// @ts-nocheck
let count = 0;



$('#q').prop('width', window.screen.width);
$('#q').prop('height', window.screen.height);

var myDate = new Date();
var h, m, s
myDate.getHours() < 10 ? h = "0" + myDate.getHours() : h = myDate.getHours();
myDate.getMinutes() < 10 ? m = "0" + myDate.getMinutes() : m = myDate.getMinutes();
myDate.getSeconds() < 10 ? s = "0" + myDate.getSeconds() : s = myDate.getSeconds();
$(".time").text(h + ":" + m + ":" + s);
setInterval(() => {
    var myDate = new Date();
    var h, m, s
    myDate.getHours() < 10 ? h = "0" + myDate.getHours() : h = myDate.getHours();
    myDate.getMinutes() < 10 ? m = "0" + myDate.getMinutes() : m = myDate.getMinutes();
    myDate.getSeconds() < 10 ? s = "0" + myDate.getSeconds() : s = myDate.getSeconds();
    $(".time").text(h + ":" + m + ":" + s);
}, 1000);

function update(data) {
    count++;
    $(".users").text(data.users);
    $(".orders").text(data.orders);
    $(".price").text(data.price);
    $(".usersTotal").text(data.usersTotal);
    $(".pre-price").text(data.PrePriceTotal);
    $(".record").text(data.record);
    $(".count").text(count);
    console.warn(data);
    $(".yesterday").text(data.yesterday);
}
$.ajax({
    type: "get",
    url: "http://server.followenjoy.cn/Home/UserNum/getUserNum",
    data: {
        time: '2018-8-13 20:30:00'
    },
    async: true,
    success: (str) => {
        var data = $.parseJSON(str)
        update(data);
    },
    error: function () {

    }
});
setInterval(() => {
    $.ajax({
        type: "get",
        url: "http://server.followenjoy.cn/Home/UserNum/getUserNum",
        data: {
            time: '2018-8-13 20:30:00'
        },
        async: true,
        success: (str) => {
            var data = $.parseJSON(str)
            update(data);
        },
        error: function () {

        }
    });

}, 10000)