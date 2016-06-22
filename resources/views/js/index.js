var indexModule = angular.module('ionicApp', ['ionic']);

indexModule.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $interpolateProvider) {
    $ionicConfigProvider.tabs.position('bottom');
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');

    $stateProvider
        .state('menu', {
            url: "/menu",
            templateUrl: "templates/menu.html"
        })
        .state('menu.tabs', {
            url: "/tabs",
            views: {
                'menu' :{
                    templateUrl: "templates/tabs.html"
                }
            }
        })
        .state('menu.tabs.home', {
            url: "/home",
            views: {
                'home-tab': {
                    templateUrl: "templates/home.html"
                }
            }
        })
        .state('menu.tabs.category', {
            url: "/category/?categoryID & categoryNam",
            views: {
                'category-tab': {
                    templateUrl: "templates/category.html"
                }
            }
        })
        .state('menu.tabs.user', {
                url: "/user",
                views: {
                    'user-tab': {
                        templateUrl: "templates/user.html"
                    }
                }
            })
        .state('menu.tabs.car', {
            url: "/car",
            views: {
                'car-tab': {
                    templateUrl: "templates/car.html"
                }
            }
        })
        .state('menu.tabs.iteminfo', {
            url: "/iteminfo/?itemID & itemNam",
            views: {
                'iteminfo-tab': {
                    templateUrl: "templates/iteminfo.html"
                }
            }
        })

    $urlRouterProvider.otherwise("/menu/tabs/home");
});

//菜单
indexModule.controller('menuController', ['$scope', '$http', function ($scope, $http) {
    $http.get("category")
        .success(
            function(data, status, header, config){
                $scope.Categorys = data;
            }
        ).error(
        function(data){
            layer.msg('加载页面出错,请稍后再试...');
        }
    );
}]);

//主页
indexModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate',
    function($scope, $http, $ionicSlideBoxDelegate){
    var clientHeight=$(window).height();
    $scope.imgHeight = (clientHeight / 5) * 2 + 'px';
    $scope.homeData = [];

    $http.get("indexItem")
        .success(
            function(data, status, header, config){
                $scope.homeData = data;
                console.log($scope.homeData);

                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
            }
        ).error(
            function(data){
                console.log(data);
                layer.msg('加载页面出错,请稍后再试...');
            }
        );

    $scope.index = 0;
    $scope.go = function(index){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').slide(index);
    };
}]);

//分类商品展示
indexModule.controller('categoryController',['$scope','$stateParams',
    function($scope, $stateParams){

    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;
}]);

//物品详情
indexModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http){

    $scope.itemID = $stateParams.itemID;
    $scope.itemNam = $stateParams.itemNam;
    var clientHeight=$(window).height();
    $scope.imgHeight = (clientHeight / 5) * 2 + 'px';

    $http.get("itemInfo/" + $scope.itemID)
        .success(
            function(data, status, header, config){
                console.log(data);

                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
            }
        ).error(
            function(data){
                console.log(data);
                layer.msg('加载页面出错,请稍后再试...');
            }
    );

    $scope.index = 0;
    $scope.go = function(index){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').slide(index);
    };
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };
}]);

//用户中心
indexModule.controller('uerCenterController', ['$scope', function($scope){

}]);

//购物车
indexModule.controller('carController', ['$scope', function($scope){

}]);
