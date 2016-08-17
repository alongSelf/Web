'use strict';

var appModule = angular.module('ionicApp.filter', []);

appModule.filter('trustHtml', function ($sce) {
    return function (input) {
        return $sce.trustAsHtml(input);
    }
});

appModule.filter('toHtml', function () {
   return function (input) {
       if (input){
           var reg=new RegExp("\r\n","g");
           var reg1=new RegExp(" ","g");
           var reg3=new RegExp("\n","g");

           input = input.replace(reg,"<br/>");
           input = input.replace(reg3,"<br/>");
           input = input.replace(reg1,"&nbsp");
       }

       return input;
   } 
});

appModule.filter('clipStr', function () {
    return function (input) {
        if (input.length > 10){
            input = input.substr(0, 10) + "...";
        }

        return input;
    }
});

appModule.filter('hidePhone', function () {
    return function (input) {
        if (!input || 0 == input.length){
            return '';
        }

        var val1 = input.substring(0, 3);
        var val2 = input.substr(input.length - 3);

        return val1 + '******' + val2;
    }
});

appModule.filter('toStrDate', function () {
    return function (input) {
        var newDate = new Date();
        newDate.setTime(input * 1000);

        return newDate.toLocaleDateString();
    }
});

appModule.filter('cutUserName', function () {
    return function (input) {
        if (input.length > 3){
            var first = input.substr(0, 1);
            var last = input.substr(input.length - 2, 2);

            input = first+'***'+last;
        }

        return input;
    }
});

appModule.filter('toStrCashStatus', function () {
    return function (input) {
        if (0 == input){
            return '处理中';
        }
        if (1 == input){
            return '完成';
        }
        if (2 == input){
            return '取消';
        }
    }
});

appModule.filter('toChannelImg', function () {
    return function (input) {
        if (1 == input){
            return 'wx.jpg';
        }
        if (2 == input){
            return 'zfb.jpg';
        }

        return 'empty.png';
    }
});

appModule.filter('specToStr', function () {
    return function (input) {
        var outPut = '';
        for (var i = 0; i < input.length; i++){
            outPut = outPut + input[i].name + '：' + input[i].val + '  ';
        }

        return outPut;
    }
});

appModule.filter('orderBgImg', function () {
    return function (input) {
        if (3 == input){
            return 'complete.jpg';
        }else {
            return 'empty.png';
        }
    }
});

appModule.filter('fmtAddr', function () {
    return function (input) {
        if('string' == typeof(input)){
            input = JSON.parse(input);
        }
        return input.province + ' ' + input.city + ' ' + input.county+ ' ' +input.address;
    }
});
