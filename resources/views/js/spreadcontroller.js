'use strict';

var appModule = angular.module('ionicApp.spreadcontroller', ['ionicApp.server']);

//推广
appModule.controller('spreadController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.showQRC = false;
    $scope.showMemo = true;
    $scope.showIncome = false;
    $scope.canShowQRC = false;
    $scope.showCash = false;
    $scope.Income = 0.0;//收入分小数
    $scope.Cash = 0;//提现条件元
    $scope.doRefresh = function () {
        $http.get("canShowQRC")
            .success(
                function (data, status, header, config) {
                    $scope.canShowQRC = data.msg.canShowQRC;
                    $scope.Income = data.msg.Income/100;
                    $scope.Cash = data.msg.Cash;
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function() {
            // 停止广播ion-refresher
            $scope.$broadcast('scroll.refreshComplete');
        });
    };

    $scope.doRefresh();

    $scope.showSpreadMemo = function () {
        $scope.showMemo = true;
        $scope.showQRC = false;
        $scope.showIncome = false;
        $scope.showCash = false;
    };
    $scope.showIncomeFc = function () {
        if (!$scope.canShowQRC){
            layer.msg('尚未满足开启条件!');
            return;
        }

        $scope.showMemo = false;
        $scope.showQRC = false;
        $scope.showCash = false;
        $scope.showIncome = true;
    };
    $scope.showQRCFc = function () {
        if (!$scope.canShowQRC){
            layer.msg('尚未满足开启条件!');
            return;
        }

        $scope.showMemo = false;
        $scope.showQRC = true;
        $scope.showIncome = false;
        $scope.showCash = false;
    };
    $scope.showCashFc = function () {
        if (!$scope.canShowQRC){
            layer.msg('尚未满足开启条件!');
            return;
        }

        $scope.showCash = true;
        $scope.showMemo = false;
        $scope.showQRC = false;
        $scope.showIncome = false;
    }

    $scope.cash = function () {
        if ($scope.Income < $scope.Cash * 100){
            var errormsg = '收入满'+$scope.Cash+'元才可以提现!';
            layer.msg(errormsg);
            return;
        }
    };
}]);

//代理
appModule.controller('agentController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.agent = [];
    $scope.myAgent = function () {
        if (!$scope.agent.name || 0 == $scope.agent.name.length || checkStr($scope.agent.name)){
            layer.msg('请输入有效的名字！');
            return;
        }
        if (!$scope.agent.phone || 0 == $scope.agent.phone.length || !checkMobile($scope.agent.phone)){
            layer.msg('请输入有效联系电话！');
            return;
        }

        $http.get("agent/"+$scope.agent.name + "/" + $scope.agent.phone)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        layer.msg(data.msg);
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    }
}]);
