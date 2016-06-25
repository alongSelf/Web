'use strict';

var appModule = angular.module('ionicApp.filter', []);

appModule.filter('trustHtml', function ($sce) {
    return function (input) {
        return $sce.trustAsHtml(input);
    }
});
