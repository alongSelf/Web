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

           input = input.replace(reg,"<br/>");
           input = input.replace(reg1,"&nbsp");
       }

       return input;
   } 
});

appModule.filter('clipStr', function () {
    return function (input) {
        if (input.length > 19){
            input = input.substr(0, 19) + "......";
        }

        return input;
    }
});
