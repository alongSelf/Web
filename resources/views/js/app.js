'use strict';

var appModule = angular.module('ionicApp', ['ionic', 'ionicApp.filter', 'ionicApp.server', 'ionicApp.controller', 'ngCookies', 'ionicLazyLoad']);

appModule.config(['$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', '$interpolateProvider', function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $interpolateProvider) {
    $ionicConfigProvider.tabs.position('bottom');
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
    //$locationProvider.html5Mode(true);

    $stateProvider
        .state('tabs', {
            abstract: true,
            url: "/tabs",
            templateUrl: "resources/views/templates/tabs.html",
        })
        .state('tabs.home', {
            url: "/home",
            views: {
                'home-tab': {
                    templateUrl: "resources/views/templates/home.html",
                    controller: 'homeController'
                }
            }
        })
        .state('tabs.find', {
            url: "/find",
            views: {
                'find-tab': {
                    templateUrl: "resources/views/templates/search.html",
                    controller: 'searchController'
                }
            }
        })
        .state('tabs.category', {
            url: "/category/?categoryID & categoryNam",
            views: {
                'home-tab': {
                    templateUrl: "resources/views/templates/category.html",
                    controller: 'categoryController'
                }
            }
        })
        .state('tabs.iteminfo', {
            url: "/iteminfo/?itemID",
            views: {
                'home-tab': {
                    templateUrl: "resources/views/templates/iteminfo.html",
                    controller: 'iteminfoController'
                }
            }
        })
        .state('tabs.user', {
            url: "/user",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/user.html",
                    controller: 'uerCenterController'
                }
            }
        })
        .state('tabs.car', {
            cache: false,
            url: "/car",
            views: {
                'car-tab': {
                    templateUrl: "resources/views/templates/car.html",
                    controller: 'carController'
                }
            }
        })

    $urlRouterProvider.otherwise("/tabs/home");
}]);
