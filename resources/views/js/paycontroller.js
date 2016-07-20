'use strict';

var appModule = angular.module('ionicApp.paycontroller', ['ionicApp.server']);

//订单
appModule.controller('orderController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
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

        $scope.pages.service = [];
        $scope.pages.service.more = true;
        $scope.pages.service.page = 0;
        $scope.pages.service.data = [];

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
        $scope.subBar.serviceClicked = false;
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
        $scope.subBar.serviceClicked = false;
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
        $scope.subBar.serviceClicked = false;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 2;
        $scope.moreData = $scope.pages.evaluate.more;
        $scope.curPage = $scope.pages.evaluate.page;
        $scope.showData = $scope.pages.evaluate.data;
    };
    $scope.serviceOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.serviceClicked = true;
        $scope.subBar.deliveryClicked = false;

        $scope.type = 3;
        $scope.moreData = $scope.pages.service.more;
        $scope.curPage = $scope.pages.service.page;
        $scope.showData = $scope.pages.service.data;

        dd($scope.pages.service.data);
    };
    $scope.deliveryOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.serviceClicked = false;
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
        }else if(3 == type){
            if('more' == setType){
                $scope.pages.service.more = val;
            }else if('page' == setType){
                $scope.pages.service.page = val;
            }else if ('data' == setType){
                if(0 != val.length){
                    $scope.pages.service.data = $scope.pages.service.data.concat(val);
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
}]);
