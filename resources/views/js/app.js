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
        .state('tabs.spreadbriefintroduction', {
            url: "/spreadbriefintroduction",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/spreadbriefintroduction.html",
                    controller: 'spreadbriefintroductionController'
                }
            }
        })
        .state('tabs.income', {
            url: "/income",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/income.html",
                    controller: 'incomeController'
                }
            }
        })
        .state('tabs.concern', {
            url: "/concern",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/concern.html",
                    controller: 'concernController'
                }
            }
        })
        .state('tabs.allorder', {
            url: "/allorder",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/allorder.html",
                    controller: 'allOrderController'
                }
            }
        })
        .state('tabs.needpay', {
            url: "/needpay",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/needpay.html",
                    controller: 'needPayController'
                }
            }
        })
        .state('tabs.needconfirm', {
            url: "/needconfirm",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/needconfirm.html",
                    controller: 'needConfirmController'
                }
            }
        })
        .state('tabs.needevaluate', {
            url: "/needevaluate",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/needevaluate.html",
                    controller: 'needEvaluateController'
                }
            }
        })
        .state('tabs.customerservice', {
            url: "/customerservice",
            views: {
                'user-tab': {
                    templateUrl: "resources/views/templates/customerservice.html",
                    controller: 'customerServiceController'
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
