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
    // $(".users").text(data.users);
    console.log(data.orders)
    $(".orders").text(num(data.orders));
    $(".price").text(num(data.price));
    $(".usersTotal").text(num(data.usersTotal));
    $(".pre-price").text(num(data.PrePriceTotal));
    $(".record").text(num(data.record));
    $(".yesterday").text(num(data.yesterday));
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

upSize();
function upSize() {
    function up() {
        document.getElementsByTagName("html")[0].style.fontSize =
            window.innerWidth / 10 + "px";
    }
    up();
    window.addEventListener("resize", () => {
        up();
    });
}

function num(num){
    var allSrt=num.toString();
    var alllength=allSrt.length
    var index = allSrt.indexOf(".");
    var decimalPoint= allSrt.substring(index,alllength);
    if(decimalPoint.indexOf("+")>0){
        return num
    }


    var theNum=0;
    var pickNum=parseInt(num) 
    var length=pickNum.toString().length
    var unit="";
    if(length>9){
        theNum=(pickNum/100000000)
        unit="e";
    }else if(length>4){
        theNum=(pickNum/10000)
        unit="w";
    }else{
        return num
    }
    return theNum.toFixed(2) +unit
}