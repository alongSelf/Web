'use strict';

var appModule = angular.module('ionicApp.controller', ['ionicApp.server']);

//主页
appModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate', '$sce', '$timeout', '$window', function($scope, $http, $ionicSlideBoxDelegate, $sce, $timeout, $window){
    $scope.activityItem = [];
    $scope.itemList = [];
    $scope.Page = 0;
    $scope.moreData = true;
    $scope.innerWidth = $window.innerWidth;

    $scope.doRefresh = function () {
        $http.get("indexItem")
            .success(
                function (data, status, header, config) {
                    $scope.Page = 0;
                    $scope.moreData = true;

                    $scope.activityItem = data.activityItem;
                    $scope.itemList = makeItemList(data.homeItem, $scope.innerWidth);
                    $scope.Notice = data.notice.notice;

                    //更新轮播
                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function () {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();

    $scope.loadMore = function () {
        $scope.Page++;
        $http.get("loadMoreIndexItem/" + $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        appendItemList($scope.itemList, data, $scope.innerWidth);
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.Page--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
    };

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
appModule.controller('categoryController',['$scope','$stateParams', '$http', '$window', '$ionicHistory', function($scope, $stateParams, $http, $window, $ionicHistory){
    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;
    $scope.Page = 0;
    $scope.moreData = true;
    $scope.showBuild = false;
    $scope.innerWidth = $window.innerWidth;

    $scope.doRefresh = function () {
        $http.get("categoryInfo/" + $scope.categoryID + '/'+ $scope.Page)
            .success(
                function(data, status, header, config){
                    $scope.Page = 0;
                    $scope.moreData = true;

                    $scope.itemList = makeItemList(data, $scope.innerWidth);
                    if (0 == $scope.itemList.length){
                        $scope.showBuild = true;
                    }
                    else{
                        $scope.showBuild = false;
                    }
                }
            ).error(
            function(data){
                onError(data);
            }).finally(function() {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();

    $scope.loadMore = function () {
        $scope.Page++;
        $http.get("categoryInfo/" + $scope.categoryID + '/'+ $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        appendItemList($scope.itemList, data, $scope.innerWidth);
                    }

                    if (0 == $scope.itemList.length){
                        $scope.showBuild = true;
                    }
                    else{
                        $scope.showBuild = false;
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.Page--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
    };

    $scope.goBack = function () {
        $ionicHistory.goBack();
    };
}]);

//物品详情
appModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', '$sce', '$ionicPopover', '$cookieStore', 'carItemNumFactory', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http, $sce, $ionicPopover, $cookieStore, carItemNumFactory){
    $scope.itemID = $stateParams.itemID;
    $scope.itemInfo = [];
    $scope.slideImg = [];
    $scope.cur_price = '';
    $scope.buynum = '';
    $scope.isCancel = true;
    $scope.isBuy = true;
    $scope.PopData = {};

    //数据获取
    $scope.doRefresh = function () {
        $http.get("itemInfo/" + $stateParams.itemID)
            .success(
                function (data, status, header, config) {
                    if (data.showimg) {
                        $scope.slideImg = JSON.parse(data.showimg);
                      }
                    if (data.spec) {
                        $scope.itemSpec = JSON.parse(data.spec);
                    }

                    $scope.commentNum = data.commentNum;

                    $scope.itemInfo = data;

                    var f = parseFloat(data.cur_price);
                    $scope.cur_price = '惊爆价:￥' + f.toFixed(2);
                    $scope.buynum = data.buynum + '人付款';

                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function() {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();

    //数字验证
    $scope.checkInput = function (strVal) {
        checkInt(strVal, true);
    };
    //弹出选项
    $scope.PopData.itemNums = ['1','2','3','4','5','6','7','8','9','10'];
    $scope.popover = $ionicPopover.fromTemplateUrl('resources/views/templates/buyitempopup.html', {
        scope: $scope
    });
    $ionicPopover.fromTemplateUrl('resources/views/templates/buyitempopup.html', {
        scope: $scope
    }).then(function(popover) {
        $scope.popover = popover;
    });
    //取消
    $scope.cancel = function() {
        $scope.isCancel = true;
        $scope.popover.hide();
    };
    //确定
    $scope.confirm = function() {
        if (!checkInt($scope.PopData.chooseNum, true)) {
            return;
        }

        $scope.PopData.itemSpec = "";
        for(var specNam in $scope.itemSpec){
            var specVal = document.getElementById(specNam).value;
            $scope.PopData.itemSpec += (specNam + ":" + specVal + "  ");
        }

        if (0 != $scope.PopData.itemSpec.length){
            $scope.PopData.itemSpec = $scope.PopData.itemSpec.substring(0,
                $scope.PopData.itemSpec.length - 2);
        }

        $scope.isCancel = false;
        $scope.popover.hide();
    };
    // 在隐藏浮动框后执行
    $scope.$on('popover.hidden', function() {
        // 执行代码
        if ($scope.isCancel){
            return;
        }

        if ($scope.isBuy){
            alert('发起购买');
        }
        else {
            var car = $cookieStore.get('car');
            if(!car){
                car = [];
            }

            var info = {};
            info.carID = uuid();
            info.id = $scope.itemInfo.id;
            info.name = $scope.itemInfo.name;
            info.spec = $scope.PopData.itemSpec;
            info.num = $scope.PopData.chooseNum;//这个是字符串......
            info.price = $scope.itemInfo.cur_price;

            car.push(info);
            $cookieStore.put("car", car);

            carItemNumFactory.setCarItemNum(getCarItemNum(car));
        }
    });

    //购买
    $scope.buy = function($event){
        $scope.isBuy = true;
        $scope.popover.show($event);
        if($scope.itemSpec){
            $scope.PopData.itemSpec = $scope.itemSpec[0];
        }

        $scope.PopData.chooseNum = $scope.PopData.itemNums[0];
    };
    //加进购物车
    $scope.addInCar = function($event){
        $scope.isBuy = false;
        $scope.popover.show($event);
        if($scope.itemSpec){
            $scope.PopData.itemSpec = $scope.itemSpec[0];
        }

        $scope.PopData.chooseNum = $scope.PopData.itemNums[0];
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

//物品介绍
appModule.controller('itemContentController', ['$scope','$stateParams', '$ionicHistory', '$http', function($scope, $stateParams, $ionicHistory, $http){
    $http.get("itemContent/" + $stateParams.itemID)
        .success(
            function (data, status, header, config) {
                $scope.itemName = data.name;
                $scope.itemContent = data.content;
            }
        ).error(
        function (data) {
            onError(data);
        });

    $scope.goBack = function () {
        $ionicHistory.goBack();
    };
}]);

//评价
appModule.controller('itemEvaluatesController', ['$scope','$stateParams', '$ionicHistory', '$http', function($scope, $stateParams, $ionicHistory, $http){
    $http.get("itemName/" + $stateParams.itemID)
        .success(
            function (data, status, header, config) {
                $scope.Page = 0;
                $scope.moreData = true;

                $scope.itemName = data.name;
            }
        ).error(
        function (data) {
            onError(data);
        });

    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.Page = 0;
    $scope.moreData = true;
    $scope.loadMore = function () {
        $http.get("itemEvaluate/" + $stateParams.itemID + '/'+ $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        if ($scope.Evaluates){
                            $scope.Evaluates = $scope.Evaluates.concat(data);
                        }else {
                            $scope.Evaluates = data;
                        }
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.Page--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
        $scope.Page++;
    };

    $scope.loadMore();
}]);

//用户中心
appModule.controller('uerCenterController', ['$scope', function($scope){
    $scope.doRefresh = function () {
        $scope.$broadcast('scroll.refreshComplete');
    };

    $scope.doRefresh();
}]);

//购物车
appModule.controller('carController', ['$scope', '$cookieStore', '$ionicPopup', 'carItemNumFactory', function($scope, $cookieStore, $ionicPopup, carItemNumFactory){
    var carInfo = $cookieStore.get('car');
    $scope.itemInCar = carInfo;
    $scope.priceTotal = getCarPriceTotal(carInfo);
    $scope.showCarInfo = false;
    $scope.itemNums = ['1','2','3','4','5','6','7','8','9','10'];
    var bPopuped = false;//解决ie会弹出2次

    if (carInfo && carInfo.length > 0){
        $scope.showCarInfo = true;
    }

    $scope.checkout = function(){
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        if (bPopuped){
            return;
        }

        bPopuped = true;

        alert('发起购买');

        bPopuped = false;
    };

    //清空购物车
    $scope.clear = function(){
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        if (bPopuped){
            return;
        }

        bPopuped = true;
        var confirmPopup = $ionicPopup.confirm({
            title: '',
            template: '确定清空购物车?'
        });
        confirmPopup.then(function(res) {
            bPopuped = false;
            if(res) {
                $cookieStore.remove('car');
                $scope.itemInCar = [];
                carItemNumFactory.setCarItemNum(0);
                $scope.priceTotal = 0;
                $scope.showCarInfo = false;
            }
        });
    };

    //修改数量
    $scope.numChange = function (carID, itemNum) {
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        if (!checkInt(itemNum, true)){
            return;
        }

        for (var i = 0; i < carInfo.length; i++){
            if (carInfo[i].carID == carID){
                carInfo[i].num = itemNum;
                $cookieStore.put("car", carInfo);
                carItemNumFactory.setCarItemNum(getCarItemNum(carInfo));
                $scope.priceTotal = getCarPriceTotal(carInfo);

                return;
            }
        }
    };

    //删除物品
    $scope.delete = function (carID) {
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        for (var i = 0; i < carInfo.length; i++){
            if (carInfo[i].carID == carID){
                carInfo.splice(i, 1);
                $cookieStore.put("car", carInfo);
                $scope.itemInCar = carInfo;
                carItemNumFactory.setCarItemNum(getCarItemNum(carInfo));
                $scope.priceTotal = getCarPriceTotal(carInfo);
                if (carInfo.length == 0){
                    $scope.showCarInfo = false;
                }

                return;
            }
        }
    }
}]);

appModule.controller('searchController', ['$scope', '$http', function ($scope, $http) {
    $scope.search = function (strVal) {
        if (!strVal){
            $scope.searchData = {};
            return;
        }
        if (0 == strVal.length){
            $scope.searchData = {};
            return;
        }
        if (checkStr(strVal)){
            $scope.searchData = {};
            return;
        }

        $http.get("search/" + strVal)
            .success(
                function(data, status, header, config){
                    $scope.searchData = data;
                }
            ).error(
            function(data){
                onError(data);
                $scope.searchData = {};
            }
        );
    }

    $scope.Categorys = [];
    $scope.doRefresh = function () {
        $http.get("categorys")
            .success(
                function(data, status, header, config){
                    $scope.Categorys = data;
                }
            ).error(
            function(data){
                onError(data);
            }).finally(function() {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();
}]);
