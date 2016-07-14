'use strict';

var appModule = angular.module('ionicApp',
    ['ionic', 'ionicApp.filter', 'ionicApp.server', 'ngCookies', 'ionicLazyLoad',
        'ionicApp.shopcontroller', 'ionicApp.usercontroller', 'ionicApp.spreadcontroller', 'ionicApp.paycontroller']);

appModule.config(['$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', '$interpolateProvider', '$locationProvider', function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $interpolateProvider, $locationProvider) {
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
        .state('tabs.iteminfo', {
            url: "/iteminfo/:itemID",
            views: {
                'home-tab': {
                    templateUrl: "resources/views/templates/iteminfo.html",
                    controller: 'iteminfoController'
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
            url: "/category/:categoryID/:categoryNam",
            views: {
                'find-tab': {
                    templateUrl: "resources/views/templates/category.html",
                    controller: 'categoryController'
                }
            }
        })
        .state('tabs.finditeminfo', {
            url: "/finditeminfo/:itemID",
            views: {
                'find-tab': {
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
        .state('tabs.userinfo', {
            url: "/userinfo",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/userinfo.html",
                    controller: 'userInfoController'
                }
            }
        })
        .state('tabs.addr', {
            url: "/addr",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/addr.html",
                    controller: 'addrController'
                }
            }
        })
        .state('tabs.order', {
            url: "/order",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/order.html",
                    controller: 'orderController'
                }
            }
        })
        .state('tabs.spread', {
            url: "/spread",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/spread.html",
                    controller: 'spreadController'
                }
            }
        })
        .state('tabs.agent', {
            url: "/agent",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/agent.html",
                    controller: 'agentController'
                }
            }
        })
        .state('tabs.changepsw', {
            url: "/changepsw",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/changepsw.html",
                    controller: 'changePSWController'
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
        .state('tabs.cariteminfo', {
            url: "/cariteminfo/:itemID",
            views: {
                'car-tab': {
                    templateUrl: "resources/views/templates/iteminfo.html",
                    controller: 'iteminfoController'
                }
            }
        })

    $urlRouterProvider.otherwise("/tabs/home");
}]);
