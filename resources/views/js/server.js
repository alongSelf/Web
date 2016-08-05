'use strict';

var appModule = angular.module('ionicApp.server', []);

//配置
appModule.run(['$rootScope', '$http', '$cookieStore', '$window', '$state', function($rootScope, $http, $cookieStore, $window, $state) {
    $rootScope.config = [];
    $rootScope.carItemNum = getCarItemNum($cookieStore.get('car'));
    $rootScope.isWX = isWX();

    $http.get("getConfig")
        .success(
            function(data, status, header, config){
                data.contactus = JSON.parse(data.contactus);
                $rootScope.config = data;
            }
        ).error(
        function(data){
            onError(data);
        }
    );
}]);

appModule.factory('carItemNumFactory', ['$rootScope', function ($rootScope) {
    var factory = {};

    factory.setCarItemNum = function(iNum) {
        $rootScope.carItemNum = iNum;
    };

    return factory;
}]);
