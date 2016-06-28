'use strict';

var appModule = angular.module('ionicApp.server', []);

//配置
appModule.run(['$rootScope', '$http', '$cookieStore', function($rootScope, $http, $cookieStore) {
    $rootScope.config = [];
    $rootScope.carItemNum = getCarItemNum($cookieStore.get('car'));

    $http.get("getConfig")
        .success(
            function(data, status, header, config){
                $rootScope.config = data;
                document.title = data.title;
                dd('module:run');
                dd(data);
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

