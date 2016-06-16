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
            .state('menu.tabs.search', {
                url: "/search",
                views: {
                    'search-tab': {
                        templateUrl: "templates/search.html"
                    }
                }
            })

            .state('menu.tabs.about', {
                url: "/about",
                views: {
                    'about-tab': {
                        templateUrl: "templates/about.html"
                    }
                }
            })

        $urlRouterProvider.otherwise("/menu/tabs/home");
    })
