'use strict';

var appModule = angular.module('ionicApp.shopcontroller', ['ionicApp.server']);

appModule.controller('jumpController', ['$scope', '$state', '$cookieStore', function($scope, $state, $cookieStore){
    $scope.jumpState = function () {
        var state = $cookieStore.get('state');
        if (state && 0 != state.length){
            state = state.state;
            $cookieStore.remove('state');
            if ('find' == state){
                $state.go('tabs.find');
            }else if ('user' == state){
                $state.go('tabs.user');
            }else {
                $state.go('tabs.home');
            }
        }else {
            $state.go('tabs.home');
        }
    };

    $scope.jumpState();
}]);

//主页
appModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate', '$sce', '$timeout', '$window', '$cookieStore', '$state', function($scope, $http, $ionicSlideBoxDelegate, $sce, $timeout, $window, $cookieStore, $state){
    $scope.activityItem = [];
    $scope.itemList = [];
    $scope.Page = 0;
    $scope.moreData = true;
    var innerWidth = $window.innerWidth > getMaxW() ? getMaxW(): $window.innerWidth;
    $scope.perItemWidth = getColStyle(parseInt(getItemListImgH() / innerWidth * 100));
    $scope.loaded = true;
    $scope.doRefresh = function () {
        $scope.loaded = true;
        $http.get("indexItem")
            .success(
                function (data, status, header, config) {
                    $scope.Page = 0;
                    $scope.moreData = true;
                    $scope.itemList = [];

                    $scope.activityItem = data.activityItem;
                    $scope.Categorys= data.category;
                    $scope.Notice = data.notice.notice;
                    if (0 != data.homeItem.length){
                        $scope.itemList = makeItemList(data.homeItem, $scope.Categorys, innerWidth);
                    }

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
                $scope.loaded = false;
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
                        $scope.itemList = appendItemList($scope.itemList, data, $scope.Categorys, innerWidth);
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
    });

    $scope.setSlideImgStyle = function () {
        $scope.innerWidth = $window.innerWidth > getMaxW() ? getMaxW(): $window.innerWidth;
        var per = $scope.innerWidth/getMaxW();
        $scope.slideImgH = parseInt(getSlideImgH() * per) + 'px';

        $scope.slideImgStyle = {
            "width" : "100%",
            "height" : $scope.slideImgH,
            "border-radius" : "10px"
        };
    };
    $scope.setSlideImgStyle();

    $(window).resize(function(){
         $scope.$apply(function(){
             $scope.setSlideImgStyle();
        });

        innerWidth = $window.innerWidth > getMaxW() ? getMaxW(): $window.innerWidth;
        $scope.perItemWidth = getColStyle(parseInt(getItemListImgH() / innerWidth * 100));
        $scope.itemList = reMakeList($scope.itemList, $scope.Categorys, innerWidth);
    });

}]);

//分类商品展示
appModule.controller('categoryController',['$scope','$stateParams', '$http', '$window', '$ionicHistory', function($scope, $stateParams, $http, $window, $ionicHistory){
    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;
    $scope.Page = 0;
    $scope.moreData = true;
    $scope.showBuild = false;
    var innerWidth = $window.innerWidth > getMaxW() ? getMaxW(): $window.innerWidth;
    $scope.perItemWidth = getColStyle(parseInt(getItemListImgH() / innerWidth * 100));
    $scope.loaded = true;

    $scope.doRefresh = function () {
        $scope.Page = 0;
        $scope.loaded = true;
        $http.get("categoryInfo/" + $scope.categoryID + '/'+ $scope.Page)
            .success(
                function(data, status, header, config){
                    $scope.moreData = true;
                    $scope.itemList = [];

                    $scope.Categorys = data.category;
                    $scope.itemList = makeItemList(data.items, $scope.Categorys, innerWidth);
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
                $scope.loaded = false;
            }
        );
    };

    $scope.doRefresh();

    $(window).resize(function(){
        innerWidth = $window.innerWidth > getMaxW() ? getMaxW(): $window.innerWidth;
        $scope.perItemWidth = getColStyle(parseInt(getItemListImgH() / innerWidth * 100));
        $scope.itemList = reMakeList($scope.itemList, $scope.Categorys, innerWidth);
    });

    $scope.loadMore = function () {
        $scope.Page++;
        $http.get("categoryInfo/" + $scope.categoryID + '/'+ $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        $scope.itemList = appendItemList($scope.itemList, data, $scope.Categorys, innerWidth);
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
appModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', '$sce', '$ionicPopover', '$cookieStore', 'carItemNumFactory', '$window', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http, $sce, $ionicPopover, $cookieStore, carItemNumFactory, $window){
    $scope.itemID = $stateParams.itemID;
    $scope.itemInfo = [];
    $scope.slideImg = [];
    $scope.cur_price = '';
    $scope.buynum = '';
    $scope.isCancel = true;
    $scope.showCon = false;
    $scope.showEv = false;
    $scope.showInfo = true;
    $scope.PopData = {};

    $scope.imgContent = [];
    $scope.imgContentMore = true;
    $scope.imgContentIndex = 1;

    $scope.getImgContent = function () {
        var content = $scope.itemInfo.content;
        if ($scope.imgContentIndex >= content.length){
            $scope.imgContentMore = false;
            return;
        }
        $scope.imgContent.push(content[$scope.imgContentIndex]);
        $scope.imgContentIndex++;
    };

    //数据获取
    $scope.doRefresh = function () {
        $http.get("itemInfo/" + $stateParams.itemID)
            .success(
                function (data, status, header, config) {
                    data.content = JSON.parse(data.content);
                    if (0 != data.content.length){
                        $scope.firstContent = data.content[0];
                    }
                    if (data.showimg) {
                        $scope.slideImg = JSON.parse(data.showimg);
                      }
                    if (data.spec) {
                        $scope.itemSpec = JSON.parse(data.spec);
                    }

                    $scope.commentNum = data.commentNum;

                    $scope.itemInfo = data;

                    $scope.imgContent = [];
                    $scope.imgContentMore = true;
                    $scope.imgContentIndex = 1;
                    $scope.getImgContent();

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

        $scope.PopData.itemSpecStr='';
        $scope.PopData.itemSpec = [];
        for(var specNam in $scope.itemSpec){
            var specInfo = {}
            specInfo.name = specNam;
            specInfo.val = $scope.PopData[specNam].Spec;

            $scope.PopData.itemSpecStr += (specNam + ":" + $scope.PopData[specNam].Spec + "  ");
            $scope.PopData.itemSpec.push(specInfo);
        }

        if (0 != $scope.PopData.itemSpecStr.length){
            $scope.PopData.itemSpecStr = $scope.PopData.itemSpecStr.substring(0,
                $scope.PopData.itemSpecStr.length - 2);
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

        var car = $cookieStore.get('car');
        if(!car){
            car = [];
        }

        var info = {};
        info.carID = uuid();
        info.id = $scope.itemInfo.id;
        info.name = $scope.itemInfo.name;
        info.img = $scope.itemInfo.indeximg;
        info.spec = $scope.PopData.itemSpec;
        info.specStr = $scope.PopData.itemSpecStr;
        info.num = $scope.PopData.chooseNum;//这个是字符串......
        info.price = $scope.PopData.cur_price;
        info.unit = $scope.itemInfo.unit;

        car.push(info);
        $cookieStore.put("car", car);

        carItemNumFactory.setCarItemNum(getCarItemNum(car));
    });

    $scope.subNum = function () {
        if (0 == $scope.PopData.chooseNum.lengt){
            $scope.PopData.chooseNum = 1;
        }

        $scope.PopData.chooseNum =  parseInt($scope.PopData.chooseNum) - 1;
        if (parseInt($scope.PopData.chooseNum) <= 0){
            $scope.PopData.chooseNum = 1;
        }
    };
    $scope.addNum = function () {
        if (0 == $scope.PopData.chooseNum.length){
            $scope.PopData.chooseNum = 1;
        }

        $scope.PopData.chooseNum = parseInt($scope.PopData.chooseNum) + 1;
    };

    //加进购物车
    $scope.addInCar = function($event){
        $scope.PopData.cur_price = $scope.itemInfo.cur_price;

        $scope.popover.show($event);
        for(var specNam in $scope.itemSpec){
            if (0 != $scope.itemSpec[specNam].length){
                $scope.PopData[specNam] = [];
                $scope.PopData[specNam].Spec = $scope.itemSpec[specNam][0].val;
                if ($scope.itemSpec[specNam][0].price){
                    $scope.PopData.cur_price = $scope.itemSpec[specNam][0].price;
                }

            }
        }

        $scope.PopData.chooseNum = 1;
    };

    $scope.PopData.showPrice = function (key) {
        var val = $scope.PopData[key].Spec;
        for (var specNam in $scope.itemSpec){
            for (var index in $scope.itemSpec[specNam]){
                if ($scope.itemSpec[specNam][index].val == val){
                    if ($scope.itemSpec[specNam][index].price){
                        $scope.PopData.cur_price = $scope.itemSpec[specNam][index].price;
                    }
                }
            }
        }
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
    });

    $scope.subBar = [];
    $scope.subBar.itemClicked = true;
    $scope.showItemInfo =function () {
        $scope.showInfo = true;
        $scope.showCon = false;
        $scope.showEv = false;

        $scope.subBar.itemClicked = true;
        $scope.subBar.itemInfoClicked = false;
        $scope.subBar.evaluateClicked = false;
    };
    $scope.showContent = function () {
        $scope.showCon = true;
        $scope.showEv = false;
        $scope.showInfo = false;

        $scope.subBar.itemClicked = false;
        $scope.subBar.itemInfoClicked = true;
        $scope.subBar.evaluateClicked = false;
    };
    $scope.showEvaluate = function () {
        $scope.showCon = false;
        $scope.showEv = true;
        $scope.showInfo = false;

        $scope.subBar.itemClicked = false;
        $scope.subBar.itemInfoClicked = false;
        $scope.subBar.evaluateClicked = true;
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
                            $scope.Evaluates = $scope.Evaluates.concat(parseEvaluates(data));
                        }else {
                            $scope.Evaluates = parseEvaluates(data);
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

    $scope.loadMoreContent = function () {
        $scope.getImgContent();
        $scope.$broadcast('scroll.infiniteScrollComplete');
    };

    $scope.setSlideImgStyle = function () {
        $scope.innerWidth = $window.innerWidth > getMaxW() ? getMaxW(): $window.innerWidth;
        var per = $scope.innerWidth/getMaxW();
        $scope.slideImgH = parseInt(getSlideImgH() * per) + 'px';

        $scope.slideImgStyle = {
            "width" : "100%",
            "height" : $scope.slideImgH,
            "border-radius" : "10px"
        };
    };
    $scope.setSlideImgStyle();

    $(window).resize(function(){
        $scope.$apply(function(){
            $scope.setSlideImgStyle();
        });
    });
}]);

//购物车
appModule.controller('carController', ['$scope', '$cookieStore', '$ionicPopup', 'carItemNumFactory', '$http', '$location', '$ionicLoading', '$state','$ionicPopover', function($scope, $cookieStore, $ionicPopup, carItemNumFactory, $http, $location, $ionicLoading, $state, $ionicPopover){
    var carInfo = $cookieStore.get('car');
    $scope.itemInCar = carInfo;
    $scope.priceTotal = getCarPriceTotal(carInfo);
    $scope.showCarInfo = false;
    if (carInfo && carInfo.length > 0){
        $scope.showCarInfo = true;
    }

    $scope.chooseAddr = function (addrID) {
        $scope.AddrId = addrID;
        for (var  i = 0; i < $scope.AddrList.length; i++){
            var id = $scope.AddrList[i].id;
            if (addrID == id){
                $scope['chooseStyle'+id] =  {
                    "background-color" : "#CCCCCC"
                };
            }else{
                $scope['chooseStyle'+id] =  {
                    "background-color" : "#fff"
                };
            }
        }
    };
    //弹出选项
    $scope.popover = $ionicPopover.fromTemplateUrl('resources/views/templates/chooseaddr.html', {
        scope: $scope
    });
    $ionicPopover.fromTemplateUrl('resources/views/templates/chooseaddr.html', {
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
        if (0 == $scope.AddrId){
            layer.msg('请选择收货地址！');
            return;
        }

        $scope.isCancel = false;
        $scope.popover.hide();
    };
    // 在隐藏浮动框后执行
    $scope.$on('popover.hidden', function() {
        // 执行代码
        if ($scope.isCancel || 0 == $scope.AddrId){
            return;
        }

        $ionicLoading.show({
            template: getLoading()
        });
        
        //订单信息生成
        var orderMsg = {}
        orderMsg.price = getCarPriceTotal(carInfo);
        orderMsg.addrID = $scope.AddrId;
        orderMsg.items = [];
        for (i = 0; i < carInfo.length; i++){
            var itemInfo = {};
            itemInfo.id = carInfo[i].id;
            itemInfo.spec = carInfo[i].spec;
            itemInfo.num = parseInt(carInfo[i].num);
            itemInfo.price = carInfo[i].price;

            orderMsg.items.push(itemInfo);
        }

        $.post("newOrder",{'_token':$('meta[name="_token"]').attr('content'),'order':JSON.stringify(orderMsg)},function(data){
            if (-1 == data.status){
                layer.msg(data.msg);
                $location.path("/tabs/user")
            }else if(0 != data.status){
                layer.msg(data.msg);
            }else {
                //订单生成成功 清理购物车
                var orderID = data.msg;

                $cookieStore.remove('car');
                $scope.itemInCar = [];
                carItemNumFactory.setCarItemNum(0);
                $scope.priceTotal = 0;
                $scope.showCarInfo = false;

                //开始支付
                $state.go('tabs.carPay', {orderID: orderID});
            }

        });

        $ionicLoading.hide();
    });

        //结算
    $scope.checkout = function($event){
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        for (var i = 0; i < carInfo.length; i++){
            if (parseInt(carInfo[i].num) <= 0){
                layer.msg('亲输入数量!');
                return;
            }
        }

        $ionicLoading.show({
            template: getLoading()
        });

        $http.get("getAddr")
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        if (0 == data.msg.length){
                            layer.msg('请在用户中心完善你的收货地址!');
                            return;
                        };

                        $scope.AddrList = data.msg;
                        $scope.AddrId = 0;
                        $scope.chooseAddr($scope.AddrList[0].id);
                        $scope.popover.show($event);
                    }
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function() {
                $ionicLoading.hide();
        });
    };

    //清空购物车
    var bPopuped = false;
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

//发现
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
