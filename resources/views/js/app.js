var appModule = angular.module('ionicApp', ['ionic']);

appModule.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $interpolateProvider) {
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
            cache: false,
            url: "/iteminfo/?itemID",
            views: {
                'iteminfo-tab': {
                    templateUrl: "templates/iteminfo.html"
                }
            }
        })

    $urlRouterProvider.otherwise("/menu/tabs/home");
});

//配置
appModule.run(['$rootScope', '$http', function($rootScope, $http) {
    $rootScope.config = [];
    $rootScope.clientWidth = $(window).width();
    $rootScope.imgHeight = getSlideImgH();
    $http.get("getConfig")
        .success(
            function(data, status, header, config){
                $rootScope.config = data;
                document.title = data.title;
            }
        ).error(
        function(data){
            onError(data);
        }
    );
}])

appModule.filter('trustHtml', function ($sce) {
    return function (input) {
        return $sce.trustAsHtml(input);
    }
});

//分类
appModule.controller('menuController', ['$scope', '$http', function ($scope, $http) {
    $scope.Categorys = [];
    $http.get("categorys")
        .success(
            function(data, status, header, config){
                $scope.Categorys = data;
            }
        ).error(
        function(data){
            onError(data);
        }
    );
}]);

//主页
appModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate', '$sce', function($scope, $http, $ionicSlideBoxDelegate, $sce){
    $scope.activityItem = [];

    $http.get("indexItem")
        .success(
            function(data, status, header, config){
                $scope.activityItem = data.activityItem;
                $scope.homeItemList = createItemList(data.homeItem);

                //更新轮播
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
            }
        ).error(
        function(data){
            onError(data);
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
appModule.controller('categoryController',['$scope','$stateParams', '$http', '$sce', function($scope, $stateParams, $http, $sce){
    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;

    $http.get("categoryInfo/" + $scope.categoryID)
        .success(
            function(data, status, header, config){
                $scope.categoryItemList = createItemList(data);
            }
        ).error(
        function(data){
            onError(data);
        }
    );
}]);

//物品详情
appModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', '$sce', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http, $sce){
    $scope.itemID = $stateParams.itemID;
    $scope.itemInfo = [];
    $scope.slideImg = [];
    $scope.cur_price = '';
    $scope.buynum = '';

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
            }
        ).error(
        function(data){
            onError(data);
        }
    );

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

}]);
