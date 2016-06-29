'use strict';

var appModule = angular.module('ionicApp', ['ionic', 'ionicApp.filter', 'ionicApp.server', 'ionicApp.controller', 'ngCookies', 'ionicLazyLoad']);

appModule.config(['$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', '$interpolateProvider', function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $interpolateProvider) {
    $ionicConfigProvider.tabs.position('bottom');
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
    //$locationProvider.html5Mode(true);

    $stateProvider
        .state('menu', {
            url: "/menu",
            templateUrl: "resources/views/templates/menu.html"
        })
        .state('menu.tabs', {
            url: "/tabs",
            views: {
                'menu' :{
                    templateUrl: "resources/views/templates/tabs.html"
                }
            }
        })
        .state('menu.tabs.home', {
            url: "/home",
            views: {
                'home-tab': {
                    templateUrl: "resources/views/templates/home.html"
                }
            }
        })
        .state('menu.tabs.category', {
            url: "/category/?categoryID & categoryNam",
            views: {
                'category-tab': {
                    templateUrl: "resources/views/templates/category.html"
                }
            }
        })
        .state('menu.tabs.user', {
            url: "/user",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/user.html"
                }
            }
        })
        .state('menu.tabs.car', {
            cache: false,
            url: "/car",
            views: {
                'car-tab': {
                    templateUrl: "resources/views/templates/car.html"
                }
            }
        })
        .state('menu.tabs.search', {
            url: "/search",
            views: {
                'search-tab': {
                    templateUrl: "resources/views/templates/search.html"
                }
            }
        })
        .state('menu.tabs.iteminfo', {
            url: "/iteminfo/?itemID",
            views: {
                'iteminfo-tab': {
                    templateUrl: "resources/views/templates/iteminfo.html"
                }
            }
        })

    $urlRouterProvider.otherwise("/menu/tabs/home");
}]);
