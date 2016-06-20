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
            url: "/category/:id",
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
            url: "/iteminfo/:id & name",
            views: {
                'iteminfo-tab': {
                    templateUrl: "templates/iteminfo.html"
                }
            }
        })

    $urlRouterProvider.otherwise("/menu/tabs/home");
});

indexModule.service('initMainData',function(){
    var _name='';

    this.setName=function(name){

        _name=name;
    }
    this.getName=function(){
        return _name;
    }
});

indexModule.controller('categoryController',['$scope','$stateParams',function($scope, $stateParams){
    $scope.categoryID = $stateParams.id;
}]);

indexModule.controller('iteminfoController',['$scope','$stateParams',function($scope, $stateParams){
    $scope.itemID = $stateParams.id;
    $scope.itemNam = $stateParams.name;
}]);
