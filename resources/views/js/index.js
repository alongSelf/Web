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
            cache: false,
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

function onError(data) {
    layer.msg('加载页面出错,请稍后再试...');
}

//配置
indexModule.run(['$rootScope', '$http', function($rootScope, $http) {
    $rootScope.config = [];
    $rootScope.clientWidth = $(window).width();
    $http.get("getConfig")
        .success(
            function(data, status, header, config){
                $rootScope.config = data;
            }
        ).error(
            function(data){
                onError(data);
        }
    );
}])

//分类
indexModule.controller('menuController', ['$scope', '$http', function ($scope, $http) {
    $scope.Categorys = [];
    $http.get("categorys")
        .success(
            function(data, status, header, config){
                $scope.Categorys = data;
            }
        ).error(
            function(data){
                onError(data);
                console.log(data);
        }
    );
}]);

//物品展示
function createItemList(itemData) {
    var iCount = itemData.length;
    var clientWidth = $(window).width();

    //每列多少个
    var iLine = parseInt(clientWidth/150);
    iLine = (0 == iLine ? 1 : iLine);

    //共有多少行
    var iRow = Math.ceil(iCount / iLine);
    var html = '';
    var iIndex = 0;
    for (i = 0; i < iRow; i++)
    {
        html += '<div class="row">';
        for (j = 0; j < iLine; j++)
        {
            if (iIndex >= iCount)
            {
                for (k = j; k < iLine; k++)
                {
                    html += '<div class="col col-'+iLine+''+iLine+'"/>';
                }

                break;
            }

            var url = '#/menu/tabs/iteminfo/?itemID='+itemData[iIndex].id+'';
            html += '<div class="col col-'+iLine+''+iLine+'">';
            html += '<span>';
            html += '<a href="'+url+'"><img href="'+url+'" class="lazy" style="height: 100%; width: 100%" src="uploads/'+itemData[iIndex].indeximg+'"></a>';
            html += '</span>';
            html += '<span>';
            html += '<a href="'+url+'" style="text-decoration:none; color: #000000"><div>'+itemData[iIndex].name+'</div></a>';
            html += '</span>';
            html += '<div></div>';
            html += '<span>';
            var f = parseFloat(itemData[iIndex].cur_price);
            html += '<em style="color: red">￥'+f.toFixed(2)+'</em>';
            html += '<em style="padding-left:10px; font-size:10px; color:#999">已售：'+itemData[iIndex].buynum+'笔</em>';
            html += '</span>';

            html += '</div>';

            iIndex++;
        }
        html += '</div>';
    }

    return html;
};

function getSlideImgH() {
    var clientHeight=$(window).height();
    return (clientHeight / 5) * 2 + 'px';
};

//主页
indexModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate', '$sce', function($scope, $http, $ionicSlideBoxDelegate, $sce){
    $scope.imgHeight = getSlideImgH();
    $scope.activityItem = [];

    $http.get("indexItem")
        .success(
            function(data, status, header, config){
                $scope.activityItem = data.activityItem;

                var htmlStr = createItemList(data.homeItem);
                var objItemList = document.getElementById('homeItemList');
                $(objItemList).append(htmlStr);

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
indexModule.controller('categoryController',['$scope','$stateParams', '$http', '$sce', function($scope, $stateParams, $http, $sce){
    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;

    $http.get("categoryInfo/" + $scope.categoryID)
        .success(
            function(data, status, header, config){
                var htmlStr = createItemList(data);
                var objItemList = document.getElementById('categoryItemList'+$scope.categoryID);
                $(objItemList).append(htmlStr);
            }
        ).error(
            function(data){
                onError(data);
        }
    );
}]);

//物品详情
indexModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http){
    $scope.itemID = $stateParams.itemID;
    $scope.imgHeight = getSlideImgH();
    $scope.itemInfo = [];
    $scope.slideImg = [];

    $http.get("itemInfo/" + $scope.itemID)
        .success(
            function(data, status, header, config){
                $scope.itemInfo = data[0];
                $scope.slideImg = data[0].showimg.split(";");

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
indexModule.controller('uerCenterController', ['$scope', function($scope){

}]);

//购物车
indexModule.controller('carController', ['$scope', function($scope){

}]);
