angular.module('ionicApp', ['ionic'])

    .config(function($stateProvider, $urlRouterProvider,$ionicConfigProvider) {
        $ionicConfigProvider.tabs.position('bottom');
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
                url: "/category/*id",
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

        $urlRouterProvider.otherwise("/menu/tabs/home");
    })
