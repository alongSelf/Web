'use strict';

var appModule = angular.module('ionicApp.controller', ['ionicApp.server']);

//分类
appModule.controller('menuController', ['$scope', '$http', '$injector', function ($scope, $http, $injector) {
    $scope.Categorys = [];
    $injector.get('$ionicLoading').show({template: '加载中...'});

    $http.get("categorys")
        .success(
            function(data, status, header, config){
                $scope.Categorys = data;
                dd('menuController');
                dd($scope.Categorys);
                $injector.get('$ionicLoading').hide();
            }
        ).error(
        function(data){
            dd(data);
            $injector.get('$ionicLoading').show({template: '网络请求错误！', duration: 800, noBackdrop: true});
        }
    );
}]);

//主页
appModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate', '$sce', '$injector', function($scope, $http, $ionicSlideBoxDelegate, $sce, $injector){
    $scope.activityItem = [];
    $injector.get('$ionicLoading').show({template: '加载中...'});
    $http.get("indexItem")
        .success(
            function(data, status, header, config){
                $scope.activityItem = data.activityItem;
                $scope.homeItemList = createItemList(data.homeItem);
                dd('homeController');
                dd($scope.activityItem);
                dd($scope.homeItemList);

                //更新轮播
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);

                $injector.get('$ionicLoading').hide();
            }
        ).error(
        function(data){
            dd(data);
            $injector.get('$ionicLoading').show({template: '网络请求错误！', duration: 800, noBackdrop: true});
        }
    );

    $scope.index = 0;
    $scope.go = function(index){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').slide(index);
    };

    //页面切换后轮播可以继续
    $scope.$on('$ionicView.beforeEnter',function(){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').start();
    })
}]);

//分类商品展示
appModule.controller('categoryController',['$scope','$stateParams', '$http', '$sce', '$injector', function($scope, $stateParams, $http, $sce, $injector){
    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;
    $injector.get('$ionicLoading').show({template: '加载中...'});

    $http.get("categoryInfo/" + $scope.categoryID)
        .success(
            function(data, status, header, config){
                $scope.categoryItemList = createItemList(data);
                $injector.get('$ionicLoading').hide();
            }
        ).error(
        function(data){
            dd(data);
            $injector.get('$ionicLoading').show({template: '网络请求错误！', duration: 800, noBackdrop: true});
        }
    );
}]);

//物品详情
appModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', '$sce', '$injector', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http, $sce, $injector){
    $scope.itemID = $stateParams.itemID;
    $scope.itemInfo = [];
    $scope.slideImg = [];
    $scope.cur_price = '';
    $scope.buynum = '';
    $injector.get('$ionicLoading').show({template: '加载中...'});

    $http.get("itemInfo/" + $scope.itemID)
        .success(
            function(data, status, header, config){
                $scope.slideImg = data[0].showimg.split(";");
                $scope.itemInfo = data[0];

                var f = parseFloat(data[0].cur_price);
                $scope.cur_price = '惊爆价:￥' + f.toFixed(2);
                $scope.buynum = '已售:' + data[0].buynum;

                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);

                $injector.get('$ionicLoading').hide();
            }
        ).error(
        function(data){
            dd(data);
            $injector.get('$ionicLoading').show({template: '网络请求错误！', duration: 800, noBackdrop: true});
        }
    );

    $scope.buy = function(){
        alert('buy:' + $scope.itemID);
    };
    $scope.addInCar = function(){
        alert('addInCar:' + $scope.itemID);
    };

    $scope.index = 0;
    $scope.go = function(index){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').slide(index);
    };

    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.$on('$ionicView.beforeEnter',function(){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').start();
    })
}]);

//用户中心
appModule.controller('uerCenterController', ['$scope', function($scope){
    $scope.groups = [];
    for (var i=0; i<5; i++) {
        $scope.groups[i] = {
            name: i,
            items: [],
            show: false
        };
        for (var j=0; j<3; j++) {
            $scope.groups[i].items.push(i + '-' + j);
        }
    }

    /*
     * if given group is the selected group, deselect it
     * else, select the given group
     */
    $scope.toggleGroup = function(group) {
        group.show = !group.show;
    };
    $scope.isGroupShown = function(group) {
        return group.show;
    };
}]);

//购物车
appModule.controller('carController', ['$scope', function($scope){
    
    $scope.checkout = function(){
        alert('checkout');
    };
    $scope.delete = function(){
        alert('delete');
    };
}]);
