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
    $scope.doRefresh = function () {
        $scope.moreData = true;
        $scope.curPage = 0;
        $scope.showData = [];

        $scope.pages = [];
        $scope.pages.all = [];
        $scope.pages.all.more = true;
        $scope.pages.all.page = 0;
        $scope.pages.all.data = [];

        $scope.pages.pay = [];
        $scope.pages.pay.more = true;
        $scope.pages.pay.page = 0;
        $scope.pages.pay.data = [];

        $scope.pages.evaluate = [];
        $scope.pages.evaluate.more = true;
        $scope.pages.evaluate.page = 0;
        $scope.pages.evaluate.data = [];

        $scope.pages.delivery = [];
        $scope.pages.delivery.more = true;
        $scope.pages.delivery.page = 0;
        $scope.pages.delivery.data = [];

        $scope.$broadcast('scroll.refreshComplete');
    };
    $scope.doRefresh();

    $scope.subBar = [];
    $scope.subBar.allClicked = true;
    $scope.allOrder = function () {
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
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = true;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 1;
        $scope.moreData = $scope.pages.pay.more;
        $scope.curPage = $scope.pages.pay.page;
        $scope.showData = $scope.pages.pay.data;
    };
    $scope.evaluateOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = true;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 2;
        $scope.moreData = $scope.pages.evaluate.more;
        $scope.curPage = $scope.pages.evaluate.page;
        $scope.showData = $scope.pages.evaluate.data;
    };
    $scope.deliveryOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.deliveryClicked = true;

        $scope.type = 4;
        $scope.moreData = $scope.pages.delivery.more;
        $scope.curPage = $scope.pages.delivery.page;
        $scope.showData = $scope.pages.delivery.data;
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

    $scope.loadMore = function () {
        $http.get("showOrder/" + $scope.curPage + '/'+ $scope.type)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        if(0 != data.msg.length){
                            for (var i = 0; i < data.msg.length; i++){
                                data.msg[i].iteminfo = JSON.parse(data.msg[i].iteminfo);
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
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
        $scope.curPage++;
        $scope.setPageVal($scope.type, 'page', $scope.curPage);
    };

    $scope.loadMore();

    $scope.Cancel = function (orderID) {
        $ionicLoading.show({
            template: 'Working...'
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
    $scope.Pay = function (orderID) {
        $state.go('tabs.orderPay', {orderID: orderID});
    };
    $scope.Evaluate = function (orderID) {
        $state.go('tabs.evaluate', {orderID: orderID});
    };
}]);

function getOrder($scope, $http)
{
    $http.get("getOrder/" + $scope.orderID)
        .success(
            function (data, status, header, config) {
                if (0 != data.status){
                    layer.msg(data.msg);
                }else {
                    $scope.Order = data.msg;
                }
            }
        ).error(
        function (data) {
            onError(data);
        }
    );
}

appModule.controller('payController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', '$ionicPopup', '$stateParams', function($scope, $ionicHistory, $http, $ionicLoading, $ionicPopup, $stateParams){
    $scope.orderID = $stateParams.orderID;
    var bPopuped = false;
    $scope.goBack = function () {
        if (bPopuped){
            return;
        }
        bPopuped = true;
        var confirmPopup = $ionicPopup.confirm({
            title: '',
            template: '确定放弃本次支付?'
        });
        confirmPopup.then(function(res) {
            bPopuped = false;
            if(res) {
                $ionicHistory.goBack();
            }
        });
    };
    getOrder($scope, $http);
}]);

appModule.controller('evController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', '$stateParams', function($scope, $ionicHistory, $http, $ionicLoading, $stateParams){
    $scope.orderID = $stateParams.orderID;
    $scope.goBack = function () {       
        $ionicHistory.goBack();
    };

    getOrder($scope, $http);
}]);
