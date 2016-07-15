'use strict';

var appModule = angular.module('ionicApp.paycontroller', ['ionicApp.server']);

//订单
appModule.controller('orderController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.subBar = [];
    $scope.subBar.allClicked = true;
    $scope.allOrder = function () {
        $scope.subBar.allClicked = true;
        $scope.subBar.payClicked = false;
        $scope.subBar.confirmClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.serviceClicked = false;
    }
    $scope.payOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = true;
        $scope.subBar.confirmClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.serviceClicked = false;
    }
    $scope.confirmOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.confirmClicked = true;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.serviceClicked = false;
    }
    $scope.evaluateOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.confirmClicked = false;
        $scope.subBar.evaluateClicked = true;
        $scope.subBar.serviceClicked = false;
    }
    $scope.serviceOrder = function () {
        $scope.subBar.allClicked = false;
        $scope.subBar.payClicked = false;
        $scope.subBar.confirmClicked = false;
        $scope.subBar.evaluateClicked = false;
        $scope.subBar.serviceClicked = true;
    }
}]);
