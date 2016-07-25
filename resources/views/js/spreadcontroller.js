'use strict';

var appModule = angular.module('ionicApp.spreadcontroller', ['ionicApp.server']);

//推广
appModule.controller('spreadController', ['$scope', '$ionicHistory', '$http', '$ionicPopup', '$ionicLoading', function($scope, $ionicHistory, $http, $ionicPopup, $ionicLoading){
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
    var bPopuped = false;
    $scope.doRefresh = function () {
        $http.get("spreadInfo")
            .success(
                function (data, status, header, config) {
                    $scope.canShowQRC = data.msg.canShowQRC;
                    $scope.Income = data.msg.Income;
                    $scope.Cash = data.msg.Cash;
                    $scope.followerCount = data.msg.follower;

                    $scope.incomePage = 0;
                    $scope.moreIncomeData = true;
                    $scope.incomeInfo = [];

                    $scope.cashPage = 0;
                    $scope.moreCashData = true;
                    $scope.cashInfo = [];
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

    $scope.subBar = [];
    $scope.subBar.memoClicked = true;
    $scope.showSpreadMemo = function () {
        $scope.showMemo = true;
        $scope.showQRC = false;
        $scope.showIncome = false;
        $scope.showCash = false;

        $scope.subBar.memoClicked = true;
        $scope.subBar.QRCClicked = false;
        $scope.subBar.incomeClicked = false;
        $scope.subBar.cashClicked = false;
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

        $scope.subBar.memoClicked = false;
        $scope.subBar.QRCClicked = false;
        $scope.subBar.incomeClicked = true;
        $scope.subBar.cashClicked = false;
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

        $scope.subBar.memoClicked = false;
        $scope.subBar.QRCClicked = true;
        $scope.subBar.incomeClicked = false;
        $scope.subBar.cashClicked = false;
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

        $scope.subBar.memoClicked = false;
        $scope.subBar.QRCClicked = false;
        $scope.subBar.incomeClicked = false;
        $scope.subBar.cashClicked = true;
    }

    //提现
    $scope.cash = function () {
        if (bPopuped){
            return;
        }
        if ($scope.Income < $scope.Cash * 100){
            var errormsg = '余额大于'+$scope.Cash+'元才可提现!';
            layer.msg(errormsg);
            return;
        }

        bPopuped = true;
        $scope.cashMoney = [];
        var myPopup = $ionicPopup.show({
            template: '<input placeholder="提现金额(元)" type="number" ng-model="cashMoney.money">',
            title: '提现',
            scope: $scope,
            buttons: [
                { text: '取消',
                    onTap: function(e){
                        bPopuped = false;
                    }},
                {
                    text: '<b>确定</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$scope.cashMoney.money){
                            e.preventDefault();
                        }
                        else if (parseInt($scope.cashMoney.money) != $scope.cashMoney.money){
                            layer.msg('请输入整数!');
                            e.preventDefault();
                        }
                        else if ($scope.cashMoney.money < $scope.Cash || 0 != $scope.cashMoney.money % $scope.Cash){
                            var errormsg = '提现金额必须为'+$scope.Cash+'的整数倍!';
                            layer.msg(errormsg);
                            e.preventDefault();
                        }
                        else{
                            $ionicLoading.show({
                                template: getLoading()
                            });
                            $.post("cash",{'_token':$('meta[name="_token"]').attr('content'),'money':$scope.cashMoney.money},function(data){
                                if (0 == data.status) {
                                    $scope.Income = $scope.Income - $scope.cashMoney.money * 100;
                                    $scope.moreCashData = true;
                                }
                                layer.msg(data.msg);
                                $ionicLoading.hide();
                            });

                            bPopuped = false;
                        }
                    }
                },
            ]
        });

    };

    //显示粉丝等级
    $scope.showLevel = function (followerid) {
        $http.get("showLevel/"+followerid)
            .success(
                function (data, status, header, config) {
                    if (0 == data.status) {
                        layer.msg(data.msg+'级粉丝');
                    }else{
                        layer.msg(data.msg);
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    };

    $scope.incomePage = 0;
    $scope.moreIncomeData = true;
    $scope.loadIncomeData = function () {
        $http.get("loadIncomeData/" + $scope.incomePage)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        if (0 == data.msg.length){
                            $scope.moreIncomeData = false;
                        }else {
                            if ($scope.incomeInfo){
                                $scope.incomeInfo = $scope.incomeInfo.concat(data.msg);
                            }else {
                                $scope.incomeInfo = data.msg;
                            }
                        }
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.incomePage--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );

        $scope.incomePage++;
    };

    $scope.cashPage = 0;
    $scope.moreCashData = true;
    $scope.loadCashData = function () {
        $http.get("loadCashData/" + $scope.cashPage)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        if (0 == data.msg.length){
                            $scope.moreCashData = false;
                        }else {
                            if ($scope.cashInfo){
                                $scope.cashInfo = $scope.cashInfo.concat(data.msg);
                            }else {
                                $scope.cashInfo = data.msg;
                            }
                        }
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.cashPage--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );

        $scope.cashPage++;
    };
}]);

//代理
appModule.controller('agentController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', function($scope, $ionicHistory, $http, $ionicLoading){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.needShow = false;
    $http.get("agentShow")
        .success(
            function (data, status, header, config) {
                if (0 != data.status){
                    layer.msg(data.msg);
                }else {
                    $scope.needShow = data.msg;
                }
            }
        ).error(
        function (data) {
            onError(data);
        });

    $scope.agent = [];
    $scope.myAgent = function () {
        if (!$scope.agent.name || 0 == $scope.agent.name.length || checkStr($scope.agent.name)){
            layer.msg('请输入有效的姓名！');
            return;
        }
        if ($scope.agent.name.length < 2 || $scope.agent.name.length > 64){
            layer.msg('姓名最少2位最多64位！');
            return;
        }
        if (!$scope.agent.phone || 0 == $scope.agent.phone.length || !checkMobile($scope.agent.phone)){
            layer.msg('请输入有效联系电话！');
            return;
        }

        $ionicLoading.show({
            template: getLoading()
        });
        $.post("agent",{'_token':$('meta[name="_token"]').attr('content'),'name':$scope.agent.name, 'phone':$scope.agent.phone},function(data){
            if (0 != data.status){
                layer.msg(data.msg);
            }else {
                layer.msg(data.msg);
                $scope.needShow = false;
            }
            $ionicLoading.hide();
        });
    }
}]);
