'use strict';

var appModule = angular.module('ionicApp.paycontroller', ['ionicApp.server']);

//订单
appModule.controller('orderController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', '$state', function($scope, $ionicHistory, $http, $ionicLoading, $state){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.type = 0;
    $scope.moreData = true;
    $scope.curPage = 0;
    $scope.showData = [];
    $scope.subBar = [];
    $scope.Loading = false;
    $scope.subBar.allClicked = true;
    $scope.initData = function () {
        $scope.pages = [];
        $scope.pages.all = [];
        $scope.pages.all.more = true;
        $scope.pages.all.page = 0;
        $scope.pages.all.data = [];
        $scope.pages.all.loaded = true;

        $scope.pages.pay = [];
        $scope.pages.pay.more = true;
        $scope.pages.pay.page = 0;
        $scope.pages.pay.data = [];
        $scope.pages.pay.loaded = false;

        $scope.pages.evaluate = [];
        $scope.pages.evaluate.more = true;
        $scope.pages.evaluate.page = 0;
        $scope.pages.evaluate.data = [];
        $scope.pages.evaluate.loaded = false;

        $scope.pages.delivery = [];
        $scope.pages.delivery.more = true;
        $scope.pages.delivery.page = 0;
        $scope.pages.delivery.data = [];
        $scope.pages.delivery.loaded = false;
    };
    $scope.initData();

    $scope.allOrder = function () {
        if ($scope.Loading){
            return;
        }
        $scope.subBar.allClicked = true;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 0;
        $scope.moreData = $scope.pages.all.more;
        $scope.curPage = $scope.pages.all.page;
        $scope.showData = $scope.pages.all.data;
    };
    $scope.payOrder = function () {
        if ($scope.Loading){
            return;
        }
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = true;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 1;
        $scope.moreData = $scope.pages.pay.more;
        $scope.curPage = $scope.pages.pay.page;
        $scope.showData = $scope.pages.pay.data;
        if (!$scope.pages.pay.loaded){
            $scope.loadOrder(true);
        }
    };
    $scope.evaluateOrder = function () {
        if ($scope.Loading){
            return;
        }
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = true;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 2;
        $scope.moreData = $scope.pages.evaluate.more;
        $scope.curPage = $scope.pages.evaluate.page;
        $scope.showData = $scope.pages.evaluate.data;
        if (!$scope.pages.evaluate.loaded){
            $scope.loadOrder(true);
        }
    };
    $scope.deliveryOrder = function () {
        if ($scope.Loading){
            return;
        }
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.deliveryClicked = true;

        $scope.type = 4;
        $scope.moreData = $scope.pages.delivery.more;
        $scope.curPage = $scope.pages.delivery.page;
        $scope.showData = $scope.pages.delivery.data;
        if (!$scope.pages.delivery.loaded){
            $scope.loadOrder(true);
        }
    };

    $scope.setPageVal = function (type, setType, val) {
        if(0 == type){
            if('more' == setType){
                $scope.pages.all.more = val;
            }else if('page' == setType){
                $scope.pages.all.page = val;
            }else if ('data' == setType){
                if(0 != val.length){
                    $scope.pages.all.data = $scope.pages.all.data.concat(val);
                }
            }else {

            }
        }else if(1 == type){
            if('more' == setType){
                $scope.pages.pay.more = val;
            }else if('page' == setType){
                $scope.pages.pay.page = val;
            }else if ('data' == setType){
                if(0 != val.length){
                    $scope.pages.pay.data = $scope.pages.pay.data.concat(val);
                }
            }else {

            }
        }else if(2 == type){
            if('more' == setType){
                $scope.pages.evaluate.more = val;
            }else if('page' == setType){
                $scope.pages.evaluate.page = val;
            }else if ('data' == setType){
                if(0 != val.length){
                    $scope.pages.evaluate.data = $scope.pages.evaluate.data.concat(val);
                }
            }else {

            }
        }else if(4 == type){
            if('more' == setType){
                $scope.pages.delivery.more = val;
            }else if('page' == setType){
                $scope.pages.delivery.page = val;
            }else if ('data' == setType){
                if(0 != val.length){
                    $scope.pages.delivery.data = $scope.pages.delivery.data.concat(val);
                }
            }else {

            }
        }else {

        }
    };

    $scope.loadOrder = function (bRefresh) {
        $scope.Loading = true;
        $http.get("showOrder/" + $scope.curPage + '/'+ $scope.type)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        if(0 != data.msg.length){
                            for (var i = 0; i < data.msg.length; i++){
                                data.msg[i].iteminfo = JSON.parse(data.msg[i].iteminfo);
                                data.msg[i].addr = JSON.parse(data.msg[i].addr);
                            }

                            $scope.setPageVal($scope.type, 'data', data.msg);
                            $scope.showData = $scope.showData.concat(data.msg);
                        }else {
                            $scope.moreData = false;
                            $scope.setPageVal($scope.type, 'more', false);
                        }
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.curPage--;
                $scope.setPageVal($scope.type, 'page', $scope.curPage);
            }).finally(function () {
                if (bRefresh){
                    $scope.$broadcast('scroll.refreshComplete');
                }else {
                    $scope.$broadcast('scroll.infiniteScrollComplete');
                }
                $scope.Loading = false;
            }
        );
        $scope.curPage++;
        $scope.setPageVal($scope.type, 'page', $scope.curPage);
    };

    $scope.doRefresh = function () {
        $scope.moreData = true;
        $scope.curPage = 0;
        $scope.showData = [];

        $scope.initData();

        $scope.loadOrder(true);
    };
    $scope.doRefresh();

    $scope.loadMore = function () {
        $scope.loadOrder(false);
    };

    $scope.Cancel = function (orderID) {
        $ionicLoading.show({
            template: getLoading()
        });
        $.post("cancelOrder",{'_token':$('meta[name="_token"]').attr('content'),'id':orderID},function(data){
            if (0 == data.status) {
                $scope.pages.all.data = removeOrder(orderID, $scope.pages.all.data);
                $scope.pages.pay.data = removeOrder(orderID, $scope.pages.pay.data);
                if (0 == $scope.type){
                    $scope.showData = $scope.pages.all.data;
                }else {
                    $scope.showData = $scope.pages.pay.data;
                }
            }else {
                layer.msg(data.msg);
            }
            $ionicLoading.hide();
        });
    };

    $scope.orderQuery = function (orderID) {
        $ionicLoading.show({
            template: getLoading()
        });
        $http.get("queryOrder/"+orderID)
            .success(
                function (data, status, header, config) {
                    layer.msg(data.msg);
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function() {
                $ionicLoading.hide();
        });
    };

    $scope.Pay = function (orderID) {
        $state.go('tabs.orderPay', {orderID: orderID});
    };
    $scope.Evaluate = function (orderID) {
        $state.go('tabs.evaluate', {orderID: orderID});
    };
    $scope.Logistics = function (orderID) {
        $state.go('tabs.logistics', {orderID: orderID});
    };
}]);

function getOrder($scope, $http, showEv)
{
    $http.get("getOrder/" + $scope.orderID + '/' + showEv)
        .success(
            function (data, status, header, config) {
                if (0 != data.status){
                    layer.msg(data.msg);
                }else {
                    data.msg.iteminfo = JSON.parse(data.msg.iteminfo);
                    $scope.Order = data.msg;
                }
            }
        ).error(
        function (data) {
            onError(data);
        }
    );
}

//支付
appModule.controller('payController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', '$ionicPopup', '$stateParams', function($scope, $ionicHistory, $http, $ionicLoading, $ionicPopup, $stateParams){
    $scope.orderID = $stateParams.orderID;
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $ionicLoading.show({
        template: getLoading()
    });
    $http.get("getOrder/" + $scope.orderID + '/0')
        .success(
            function (data, status, header, config) {
                if (0 != data.status){
                    layer.msg(data.msg);
                    return;
                }
                $scope.Order = data.msg;
            }
        ).error(
        function (data) {
            onError(data);
        }).finally(function() {
            $ionicLoading.hide();
        }
    );

    function jsApiCall(){
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {
                "appId":$scope.payInfo.appId,     //公众号名称，由商户传入
                "timeStamp":$scope.payInfo.timeStamp.toString(),         //时间戳，自1970年以来的秒数
                "nonceStr":$scope.payInfo.nonceStr, //随机串
                "package":$scope.payInfo.package,
                "signType":$scope.payInfo.signType,         //微信签名方式：
                "paySign":$scope.payInfo.paySign //微信签名
            },
            function(res){
                if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                    layer.msg('支付成功！');
                    $ionicHistory.goBack();
                }else {
                    layer.msg('支付失败！');
                }
            }
        );
    };

    $scope.pay = function () {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            if (!$scope.Order.payinfo || 0 == $scope.Order.payinfo.length){
                layer.msg('参数错误！');
                return;
            }
			
            $scope.payInfo = (JSON.parse($scope.Order.payinfo)).appPayParams;
            jsApiCall();
        }
    };
}]);

//评论
appModule.controller('evController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', '$stateParams', function($scope, $ionicHistory, $http, $ionicLoading, $stateParams){
    $scope.orderID = $stateParams.orderID;
    $scope.goBack = function () {       
        $ionicHistory.goBack();
    };

    getOrder($scope, $http, 1);    
    $scope.Evaluate = function (orderID, itemID, index) {
        var pf = document.getElementById('ps'+index).value;
        var ev = document.getElementById('ev'+index).value;
        if (checkStr(ev)){
            layer.msg('请勿输入特殊字符!');
            return;
        }

        $ionicLoading.show({
            template: getLoading()
        });
        $.post("evaluate",{'_token':$('meta[name="_token"]').attr('content'),
            'itemid':itemID, 'star':pf, 'evaluate':ev, 'orderid':orderID},function(data){
            if (0 == data.status) {
                for (var i = 0; i < $scope.Order.iteminfo.items.length; i++){
                    if ($scope.Order.iteminfo.items[i].id == itemID){
                        $scope.Order.iteminfo.items[i].showEV = false;
                        break;
                    }
                }
                var bRet = true;
                for (var i = 0; i < $scope.Order.iteminfo.items.length; i++){
                    if ($scope.Order.iteminfo.items[i].showEV){
                        bRet = false;
                        break;
                    }
                }
                if (bRet){
                    $scope.goBack();
                }
            }else {
                layer.msg(data.msg);
            }
            $ionicLoading.hide();
        });
    };
}]);

//物流
appModule.controller('logisticsController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', '$stateParams', function($scope, $ionicHistory, $http, $ionicLoading, $stateParams){
    $scope.orderID = $stateParams.orderID;
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.showLogistics = false;
    $ionicLoading.show({
        template: getLoading()
    });
    $http.get("logistics/" + $scope.orderID)
        .success(
            function (data, status, header, config) {
                if (0 != data.status){
                    layer.msg(data.msg);
                }else {
                    data.msg.logistics = arrangeLogistics(data.msg.logistics);
                    $scope.Logistics = data.msg;
                    $scope.showLogistics = true;
                }
            }
        ).error(
        function (data) {
            onError(data);
        }).finally(function () {
            $ionicLoading.hide();
        }
    );
}]);
